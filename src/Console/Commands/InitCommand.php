<?php

namespace DDDCore\Console\Commands;

use RuntimeException;

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
    }


    /**
     * Build configuration.
     *
     * @return void
     */
    private function buildConfiguration(): void
    {
        $this->replaceLogging();
        $this->line('Build configuration completed...');
    }


    /**
     * Build domain.
     *
     * @return void
     */
    private function buildDomain(): void
    {
        $this->line('Building system architecture completed...');
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
     * @param $filename
     * @param $backupName
     * @return void
     */
    private function backup($filename, $backupName): void
    {
        if (!file_exists($filename)) {
            throw new RuntimeException(sprintf('File "%s" does not exist', $filename));
        }

        if (!file_exists($backupName) && !mkdir($backupName, 0777, true) && !is_dir($backupName)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $backupName));
        }

        $files = scandir($filename);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $sourcePath      = $filename.'/'.$file;
            $destinationPath = $backupName.'/'.$file;

            if (is_dir($sourcePath)) {
                $this->backup($sourcePath, $destinationPath);
            } else {
                copy($sourcePath, $destinationPath);
            }
        }

    }
}