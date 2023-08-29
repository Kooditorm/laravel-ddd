<?php

namespace App\Infrastructure\Supports;

use App\Infrastructure\Constants\ErrorCodeConstant;
use Exception;
use Godruoyi\Snowflake\Snowflake;
use Illuminate\Support\Facades\Redis;

class GenerateCode
{
    /**
     * @return string
     * @throws Exception
     */
    public static function getCode(int $length = 16): string
    {
        $mac       = hexdec((int)str_replace(':', '', (new self)->getMac()));
        $tryTime   = 0;
        $pass      = false;
        $lockKey   = 'generate_code_tmp_lock';
        $snowflake = app(Snowflake::class, [$mac, (new self)->getPid()]);
        do {
            //尝试次数+1
            $tryTime++;
            //雪花算法生成唯一id
            $id = $snowflake->id();
            //转32进制进制
            $convertStr = (new self)->convert58($id);
            //补充到16位
            $index  = 0;
            $strArr = range('A', 'Z');

            while (mb_strlen($convertStr) < $length) {
                //随机
                $str = $strArr[random_int(0, (count($strArr) - 1))];
                //赋值
                if ($index < 2) {
                    $convertStr = $str.$convertStr;
                    $index++;
                } else {
                    $convertStr .= $str;
                }
            }

            if (!Redis::exists($lockKey, $convertStr)) {
                $pass    = true;
                $tryTime = 3;
                //拿到了，暂个缓存
                Redis::setex($lockKey.$convertStr, 60, 'lock');
            }
        } while ($tryTime < 3);

        if ($pass) {
            return $convertStr;
        }
        exception(ErrorCodeConstant::EXCHANGE_CODE_CREATE_ERROR);
    }

    private function getPid(): int
    {
        $pid = getmypid();
        if (function_exists('posix_getpid')) {
            $pid = posix_getpid();
        }
        return $pid;
    }

    /**
     * 转换成32进制
     * @param  string  $num
     * @return string
     */
    private function convert58(string $num): string
    {
        $to   = 58;
        $dict = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        $ret  = '';
        do {
            $mod = bcmod($num, $to);
            $mod = $mod > mb_strlen($dict) ? mb_strlen($dict) : $mod;
            $ret = $dict[$mod].$ret; //bcmod取得高精确度数字的余数。
            $num = bcdiv($num, $to); //bcdiv将二个高精确度数字相除。
        } while ($num > 0);
        return $ret;
    }

    private function getMac(): string
    {
        $macAddr  = '';
        $ifConfig = [];

        $tempArr = [];

        if ((mb_strtolower(PHP_OS)) === 'linux') {
            @exec('ifconfig -a', $ifConfig);
        } else {
            @exec('ipconfig /all', $ifConfig);

            if (!$ifConfig) {
                $ipconfig = $_SERVER["WINDIR"]."\system32\ipconfig.exe";
                if (is_file($ipconfig)) {
                    @exec($ipconfig." /all", $ifConfig);
                } else {
                    @exec($_SERVER["WINDIR"]."\system\ipconfig.exe /all", $ifConfig);
                }
            }
        }

        foreach ($ifConfig as $item) {
            $pattern = "/[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f]/i";
            if (preg_match($pattern, $item, $tempArr)) {
                $macAddr = $tempArr[0];
                break;
            }
        }

        return str_replace('-', ':', $macAddr);
    }
}
