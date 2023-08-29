<?php
/**
 * Author: oswin
 * Time: 2021/9/4-18:27
 * Description:
 * Version: v1.0
 */

use App\Infrastructure\Exceptions\BaseException;
use Illuminate\Http\Request;

if (!function_exists('getClientIp')) {
    /**
     * @return string|null
     */
    function getClientIp(): ?string
    {
        $realIp  = null;
        $request = request();

        if (!is_null($request)) {
            $realIp = $request->getClientIp();
        } elseif (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $realIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realIp = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $realIp = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $realIp = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $realIp = getenv("HTTP_CLIENT_IP");
        } else {
            $realIp = getenv("REMOTE_ADDR");
        }

        return $realIp;
    }
}

if (!function_exists('domain_path')) {
    /**
     * Get the domain path.
     *
     * @param  string  $path
     * @return string
     */
    function domain_path(string $path = ''): string
    {
        return app_path('Domain');
    }
}

if (!function_exists('dirs')) {
    /**
     * 根据下级目录名称
     *
     * @param  string  $path
     * @param  bool  $is_complete
     * @return array
     */
    function dirs(string $path = '', bool $is_complete = false): array
    {
        $dir = [];

        if (is_dir($path)) {
            $data = scandir($path);
            foreach ($data as $datum) {
                if (!in_array($datum, ['.', '..'])) {
                    if ($is_complete) {
                        $datum = $path.DIRECTORY_SEPARATOR.$datum;
                    }
                    $dir[] = $datum;
                }
            }
        }

        return $dir;
    }
}

if (!function_exists('files')) {
    /**
     * 获取目录下所有文件
     *
     * @param  string  $path
     * @param  bool  $is_complete
     * @return array
     */
    function files(string $path, bool $is_complete = false): array
    {
        $files = [];

        $data = dirs($path);

        foreach ($data as $datum) {
            if (!empty($datum) && !is_dir($datum)) {
                $file = $path.DIRECTORY_SEPARATOR.$datum;
                if (!is_dir($file)) {
                    $files[] = $is_complete ? $file : $datum;
                }
            }
        }

        return $files;
    }
}

if (!function_exists('extract_namespace')) {
    /**
     * 获取文件的命名空间
     *
     * @param  string  $file
     * @return string|null
     */
    function extract_namespace(string $file): ?string
    {
        $ns = null;
        if (is_file($file)) {
            $handle = fopen($file, 'rb');
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (strpos($line, 'namespace') === 0) {
                        $parts = explode(' ', $line);
                        $ns    = rtrim(trim($parts[1]), ';');
                        break;
                    }
                }
                fclose($handle);
            }
        }

        return $ns;
    }
}

if (!function_exists('is_mobile')) {
    /**
     * 判断是否手机号码
     * @param  string  $str
     * @return bool
     */
    function is_mobile(string $str): bool
    {
        $rtn = false;

        if (!empty($str) && preg_match('/^1\d{10}$/', $str)) {
            $rtn = true;
        }

        return $rtn;
    }
}
if (!function_exists('is_email')) {
    /**
     * 判断是否手机号码
     * @param  string  $str
     * @return bool
     */
    function is_email(string $str): bool
    {
        $rtn = false;

        if (!empty($str) && preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $str)) {
            $rtn = true;
        }

        return $rtn;
    }
}

if (!function_exists('exception')) {
    /**
     * @throws BaseException
     */
    function exception(array $error)
    {
        throw new BaseException($error);
    }
}

if (!function_exists('GUID')) {

    /**
     * 生成随机字符串
     *
     * @param  int  $length
     * @return string
     * @throws Exception
     */
    function GUID(int $length = 16): string
    {
        return bin2hex(random_bytes($length / 2));
    }
}


if (!function_exists('setHeader')) {
    /**
     * 设置头信息
     * @param $request
     * @param $key
     * @param  string  $value
     * @return mixed
     */
    function setHeader($request, $key, string $value = '')
    {
        if (!is_null($request)) {
            if (($request instanceof Request) === false) {
                $request = request();
            }
            if (is_array($key)) {
                foreach ($key as $k => $datum) {
                    $request->headers->set($k, $datum);
                }
            } elseif (is_string($key) && !empty($key) && !empty($value)) {
                $request->headers->set($key, $value);
            }
        }

        return $request;
    }
}


if (!function_exists('getHeader')) {
    /**
     * 获取头信息
     * @param $key
     * @param  null  $request
     * @return array|mixed|string
     */
    function getHeader($key, $request = null)
    {
        $value = '';

        if (($request instanceof Request) === false) {
            $request = request();
        }

        if ($request->hasHeader($key)) {
            $val = $request->header($key);
            if (is_array($val)) {
                $value = head($val);
            } else {
                $value = $val;
            }
        }

        return $value;
    }
}

if (!function_exists('is_json')) {
    /**
     * 判断是否一个字符串是否json
     *
     * @param  string  $json
     * @return bool
     */
    function is_json(string $json): bool
    {
        try {
            json_decode($json, false, 512, JSON_THROW_ON_ERROR);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

if (!function_exists('os')) {
    /**
     * @param  string  $agent
     * @return string
     */
    function os(string $agent): string
    {
        if (false !== stripos($agent, 'win') && preg_match('/nt 6.1/i', $agent)) {
            return 'Windows 7';
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 6.2/i', $agent)) {
            return 'Windows 8';
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 10.0/i', $agent)) {
            return 'Windows 10';
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 11.0/i', $agent)) {
            return 'Windows 11';
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 5.1/i', $agent)) {
            return 'Windows XP';
        }
        if (false !== stripos($agent, 'linux')) {
            return 'Linux';
        }
        if (false !== stripos($agent, 'mac')) {
            return 'Mac';
        }
        return 'unknown';
    }
}

if (!function_exists('browser')) {
    function browser(string $agent): string
    {
        if (false !== stripos($agent, "MSIE")) {
            return 'MSIE';
        }
        if (false !== stripos($agent, "Edg")) {
            return 'Edge';
        }
        if (false !== stripos($agent, "Chrome")) {
            return 'Chrome';
        }
        if (false !== stripos($agent, "Firefox")) {
            return 'Firefox';
        }
        if (false !== stripos($agent, "Safari")) {
            return 'Safari';
        }
        if (false !== stripos($agent, "Opera")) {
            return 'Opera';
        }
        return 'unknown';
    }
}

if (!function_exists('ipToRegion')) {
    /**
     * @param  string  $ip
     * @return string
     */
    function ipToRegion(string $ip): string
    {
        return app(Ip2Region::class)->simple($ip);
    }
}


