<?php

namespace DDDCore\Libraries\Prettus;

use DDDCore\Traits\FieldTrait;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Prettus\Repository\Generators\FileAlreadyExistsException;
use Prettus\Repository\Generators\Generator as PrettusGenerator;

abstract class Generator extends PrettusGenerator
{
    use FieldTrait;

    /**
     * The placeholder for repository bindings
     *
     * @var string
     */
    public string $bindStartPlaceholder = '//:fields:';

    /**
     * The placeholder for repository bindings
     *
     * @var string
     */
    public string $bindEndPlaceholder = '//:end-fields:';


    /**
     * Run the generator.
     *
     * @return int|bool
     * @throws FileAlreadyExistsException
     * @throws FileNotFoundException
     */
    public function run()
    {
        $this->setUp();
        $action = $this->options['action'] ?? '';
        $path   = $this->getPath();

        if ($action === 'del') {
            if ($this->filesystem->exists($path)) {
                $rtn = $this->filesystem->delete($path);
                if ($rtn) {
                    $dir = dirname($this->getPath());
                    if (empty($this->filesystem->allFiles($dir))) {
                        $this->filesystem->deleteDirectory($dir);
                    }
                }
            }
            if ($this->checkLastAction()) {
                throw new FileAlreadyExistsException($path, 2);
            }
            return true;
        }

        if ($this->filesystem->exists($path)) {
            $filedReplace = $this->getFilling();
            if (method_exists($this, 'replace')) {
                $filedReplace = $this->replace();
            }
            $content = $this->filesystem->get($path);
            $search  = $this->getStrBetween($content, $this->bindStartPlaceholder, $this->bindEndPlaceholder);
            $replace = $this->getStrBetween($filedReplace, $this->bindStartPlaceholder, $this->bindEndPlaceholder);
            $this->filesystem->put($path, str_replace($search, $replace, $content));
            if ($this->checkLastAction()) {
                throw new FileAlreadyExistsException($path, 1);
            }
            return true;

        }

        return parent::run();
    }


    /**
     * @return int|bool
     * @throws FileAlreadyExistsException
     */
    public function onlyRun()
    {
        $this->setUp();
        $action  = $this->options['action'] ?? '';
        $residue = $this->options['residue'] ?? 0;
        $path    = $this->getPath();

        if ($action === 'del') {
            $dTotal = count($this->filesystem->directories($this->getBasePath()));
            if ($dTotal === $residue & $this->filesystem->exists($path)) {
                $rtn = $this->filesystem->delete($path);
                if ($rtn) {
                    $this->deleteDir();
                }

                if ($this->checkLastAction()) {
                    throw new FileAlreadyExistsException($path, 2);
                }
            }
            return true;
        }

        return parent::run();
    }

    /**
     * delete dir
     *
     * @return void
     */
    private function deleteDir(): void
    {
        $dir = [
            dirname($this->getPath()),
            $this->getBasePath()
        ];

        array_map(function ($path) {
            if (empty($this->filesystem->allFiles($path))) {
                $this->filesystem->deleteDirectory($path);
            }
        }, $dir);

    }

    /**
     * check last action
     *
     * @return bool
     */
    private function checkLastAction(): bool
    {
        $lastAction = $this->options['lastAction'] ?? false;
        return (bool)$lastAction;
    }
}
