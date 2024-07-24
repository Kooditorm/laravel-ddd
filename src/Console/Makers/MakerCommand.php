<?php

namespace DDDCore\Console\Makers;

use Exception;
use Illuminate\Console\Command;


/**
 * @class MakerCommand
 * @package DDDCore\Console\Makers
 */
class MakerCommand extends Command
{

    /** @var bool 是否领域模块唯一文件 */
    protected bool $single = false;

    /**
     * 获取唯一值
     *
     * @return bool
     */
    public function single(): bool
    {
        return $this->single;
    }

    /**
     * 领域文件生成提示
     *
     * @param  Exception|null  $e
     * @param  string  $name
     * @return void
     */
    protected function tips(?Exception $e = null, string $name = ''): void
    {
        if (empty($name)){
            [, $name] = explode(':', $this->name);
        }

        if ($e instanceof Exception) {
            if ($e->getCode() === 1) {
                $this->info($name." updated successfully.");
            } elseif ($e->getCode() === 2) {
                $this->info($name." remove successfully.");
            } else {
                $this->error($name.' already exists!');
            }
        } else {
            $this->info($name.' created successfully.');
        }
    }

}
