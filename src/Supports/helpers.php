<?php

namespace DDDCore\Supports;

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
                $action = compact($controller, $method);
            }
        }


        return $action;
    }
}
