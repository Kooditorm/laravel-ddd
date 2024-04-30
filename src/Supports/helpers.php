<?php

use Illuminate\Http\Request;

if (!function_exists('getActions')) {

    /**
     * @param $request
     * @return array
     */
    function getActions($request = null): array
    {
        $action = [];

        if (($request instanceof Request) === false) {
            $request = request();
        }

        $route = $request->route();

        if (!empty($route)) {
            $actions = $route->getAction();

            if (isset($actions['controller'])) {
                [$controller, $method] = explode('@', $actions['controller']);
                $action = compact('controller', 'method');
            }
        }


        return $action;
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
