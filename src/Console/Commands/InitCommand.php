<?php

namespace DDDCore\Console\Commands;

use DDDCore\Console\Inits\HandlerGenerator;
use Prettus\Repository\Generators\FileAlreadyExistsException;
use RuntimeException;

/**
 * @class InitCommand
 * @package DDDCore\Console\Commands
 */
class InitCommand extends BaseCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Domain driven system initialization command';

    /**
     * The application path.
     *
     * @var ?string $app_path
     */
    protected ?string $app_path = null;

    public function __construct(?string $name = null)
    {

        if (!empty($name)) {
            $this->signature = str_replace('init', 'ddd:'.$name, $this->signature);
        }

        if (is_null($this->app_path)) {
            $this->app_path = app_path();
        }


        parent::__construct();
    }

    /**
     * @inheritDoc
     * @return void
     */
    public function handle(): void
    {
        $this->line('Start initializing project...');
        $this->init();
    }

    /**
     * Initialize system architecture
     *
     * @return void
     */
    private function init(): void
    {
        $this->buildConfiguration();
        $this->buildDomain();
    }


    /**
     * Build configuration.
     *
     * @return void
     */
    private function buildConfiguration(): void
    {
        $this->replaceLogging();
        $this->replaceHandler();
        $this->line('Build configuration completed...');
    }


    /**
     * Build domain.
     *
     * @return void
     */
    private function buildDomain(): void
    {
        $domain_file = app_path('Domain');
        if (!mkdir($concurrentDirectory = dirname($domain_file), 0777, true) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        $this->line('Building system architecture completed...');
    }

    private function buildInterface(): void
    {
        $interface_file = app_path('Interfaces');
        if (!mkdir($concurrentDirectory = dirname($interface_file), 0777, true) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
    }

    /**
     * Replace logging configuration.
     *
     * @return void
     */
    private function replaceLogging(): void
    {
        $logging_file = config_path('logging.php');
        $loggingText  = file_get_contents($logging_file);
        if (file_exists($logging_file)) {
            rename($logging_file, $logging_file.'.backup');
        }
        $logging = dirname(__DIR__, 3).'/config/logging.php';
        try {
            copy($logging, $logging_file);
        } catch (RuntimeException $exception) {
            rename($logging_file.'.backup', $logging_file);
        } finally {
            if (!file_exists($logging_file)) {
                file_put_contents($logging_file, $loggingText);
            }

            if (file_exists($logging_file.'.backup')) {
                unlink($logging_file.'.backup');
            }
        }
    }

    /**
     * Replace handler Exception
     *
     * @return void
     */
    private function replaceHandler():void
    {
        $handler_file = $this->app_path . '/Exceptions/Handler.php';
        $handlerText  = file_get_contents($handler_file);
        if (file_exists($handler_file)) {
            rename($handler_file, $handler_file.'.backup');
        }
        try {
            (new HandlerGenerator())->run();
        } catch (RuntimeException|FileAlreadyExistsException $exception) {
            rename($handler_file.'.backup', $handler_file);
        } finally {
            if (!file_exists($handler_file)) {
                file_put_contents($handler_file, $handlerText);
            }

            if (file_exists($handler_file.'.backup')) {
                unlink($handler_file.'.backup');
            }
        }
    }

//    /**
//     * @param $filename
//     * @param $backupName
//     * @return void
//     */
//    private function backup($filename, $backupName): void
//    {
//        if (!file_exists($filename)) {
//            throw new RuntimeException(sprintf('File "%s" does not exist', $filename));
//        }
//
//        if (!file_exists($backupName) && !mkdir($backupName, 0777, true) && !is_dir($backupName)) {
//            throw new RuntimeException(sprintf('Directory "%s" was not created', $backupName));
//        }
//
//        $files = scandir($filename);
//
//        foreach ($files as $file) {
//            if ($file === '.' || $file === '..') {
//                continue;
//            }
//
//            $sourcePath      = $filename.'/'.$file;
//            $destinationPath = $backupName.'/'.$file;
//
//            if (is_dir($sourcePath)) {
//                $this->backup($sourcePath, $destinationPath);
//            } else {
//                copy($sourcePath, $destinationPath);
//            }
//        }
//
//    }
}
