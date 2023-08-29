<?php
/**
 * Author: oswin
 * Time: 2021/11/2-20:40
 * Description:
 * Version: v1.0
 */

namespace App\Infrastructure\Supports;

use App\Domain\User\Constants\UserErrorConstant;
use App\Domain\User\Exceptions\UserException;
use App\Infrastructure\Constants\ErrorCodeConstant;
use App\Infrastructure\Exceptions\BaseException;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate
{

    /**
     * 获取token
     * @throws BaseException
     */
    public static function getToken(string $guard = '', array $params = [])
    {
        try {
            $params = $params ?: [
                'username' => request('username'),
                'password' => request('password'),
            ];

            $token = self::guard($guard)->attempt($params);

            if (!empty($token)) {
                return [
                    'access_token' => $token,
                    'user'         => self::guard($guard)->user(),
                    'token_type'   => 'bearer',
                    'expires_in'   => self::guard($guard)->factory()->getTTL() * 60
                ];
            }
            throw new UserException(UserErrorConstant::LOGIN_FAIL);
        } catch (Exception $e) {
            if ($e instanceof UserException) {
                throw $e;
            }
            exception(ErrorCodeConstant::SERVER_ERROR);
        }
    }

    public static function guard(string $guard = ''): Guard
    {
        return Auth::guard($guard);
    }


    public static function getTokenFormUser(JWTSubject $user): array
    {
        return [
            'access_token' => JWTAuth::fromUser($user),
            'token_type'   => 'bearer',
            'expires_in'   => JWTAuth::factory()->getTTL() * 60
        ];
    }


}
