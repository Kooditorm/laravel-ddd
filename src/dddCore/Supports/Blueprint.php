<?php
/**
 * Author: oswin
 * Time: 2022/6/8-14:10
 * Description:
 * Version: v1.0
 */

namespace App\Infrastructure\Supports;

use Closure;

class Blueprint
{
    public function tableComment(): Closure
    {
        return function (string $comment) {
            $this->addCommand('tableComment', compact('comment'));
        };
    }

}
