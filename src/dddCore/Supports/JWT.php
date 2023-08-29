<?php

namespace App\Infrastructure\Supports;

use App\Domain\System\Constants\SystemCacheConstant;
use App\Domain\User\Constants\UserCacheConstant;
use App\Domain\User\Constants\UserErrorConstant;
use App\Infrastructure\Constants\ErrorCodeConstant;
use App\Infrastructure\Exceptions\BaseException;
use App\Infrastructure\Facades\RedisCache;
use JsonException;
use RedisException;
use Tymon\JWTAuth\Facades\JWTAuth;


class JWT
{

    /**
     * token 信息
     * @var array
     */
    protected array $payload = [];

    /**
     * 用户信息
     *
     * @var array
     */
    protected array $user = [];

    /**
     * 应用ID
     *
     * @var int
     */
    protected int $appId = 0;

    /**
     * @throws BaseException|JsonException
     */
    public function __construct()
    {
        $this->getPayload();
        $this->user();
    }

    /**
     * @return array
     * @throws BaseException|JsonException
     */
    public function user(): array
    {
        if (!empty($this->payload)) {
            if (empty($this->user)) {
                $this->user = $this->getCacheUser($this->payload['sub']);
                $is_super   = false;
                $userRoles  = $this->user['role'] ?? [];
                if (!empty($userRoles)) {
                    foreach ($userRoles as $role) {
                        if ($role['is_super'] === 1) {
                            $is_super = true;
                            break;
                        }
                    }
                }

                $this->user['is_super'] = $is_super;
                if ($is_super === false) {
                    $this->user['scope']    = $this->scope();
                }
            }
            if (!empty($this->user)) {
                return $this->user;
            }
        }
        exception(UserErrorConstant::NO_LOGIN);
    }

    /**
     * 获取用户权限
     *
     * @return array
     * @throws BaseException|JsonException
     */
    public function permission(): array
    {
        $permission = [];
        $appId      = $this->getAppId();

        if (!empty($this->user) && !empty($this->user['role'])) {
            collect($this->user['role'])->map(function ($role) use (&$permission, $appId) {
                $cachePermission = $this->getCacheMenu($role['id']);
                $cacheMenu       = $cachePermission['menu'] ?? [];
                $permission      = array_merge($permission, $cacheMenu[$appId] ?? []);
            })->toArray();
        }
        return $permission;
    }

    /**
     * 获取数据权限
     * @return array
     * @throws BaseException
     * @throws JsonException
     */
    public function scope(): array
    {
        $scope = [];
        $appId = $this->getAppId();

        if (!empty($this->user) && !empty($this->user['role'])) {
            collect($this->user['role'])->map(function ($role) use (&$scope, $appId) {
                $cacheScopeData = $this->getCacheMenu($role['id']);
                $cacheScopeType = $cacheScopeData['scope'] ?? [];
                if (!empty($cacheScopeType)) {
                    $scope = array_merge($scope, $cacheScopeType[$appId]);
                }

            })->toArray();
        }

        return $scope;
    }

    /**
     * @return bool
     * @throws BaseException|JsonException
     */
    public function isSuper(): bool
    {
        $rtn = false;
        if (!empty($this->user())) {
            $rtn = $this->user['is_super'];
        }
        return $rtn;
    }

    /**
     * @return int
     * @throws BaseException
     * @throws JsonException
     */
    public function getAppId(): int
    {
        if (empty($this->appId)) {
            $appKey = env('APP_SYSTEM_KEY');
            if (empty($appKey)) {
                throw new BaseException(ErrorCodeConstant::NO_PERMISSION);
            }
            $appInfo     = $this->getAppCache($appKey);
            $this->appId = $appInfo['id'] ?? 0;

//            if (empty($this->appId)) {
//                throw new BaseException(ErrorCodeConstant::NO_PERMISSION);
//            }
        }
        return $this->appId;
    }

    /**
     * @return array
     * @throws BaseException
     */
    public function getPayload(): array
    {
        if (!empty(JWTAuth::getToken())) {
            if (empty($this->payload)) {
                try {
                    $this->payload = JWTAuth::parseToken()->getPayload()->toArray();
                } catch (\Exception $e) {
                    $this->payload = [];
                }
            }
            return $this->payload;
        }

        exception(UserErrorConstant::NO_LOGIN);
    }

    /**
     * token 无效
     *
     * @param  int  $id
     * @return void
     * @throws RedisException
     */
    public function invalidate(int $id = 0): void
    {
        if (empty($id)) {
            $token = JWTAuth::getToken();
            $id    = $this->user['id'];
        } else {
            $token = RedisCache::hget(UserCacheConstant::USER_CACHE_USER_TOKEN, $id);
        }
        if (!empty($token)) {
            JWTAuth::setToken($token)->invalidate();
            RedisCache::hdel(UserCacheConstant::USER_CACHE_USER_TOKEN, $id);
        }
    }

    /**
     * 获取缓存的用户数据
     * @param  int  $userId
     * @return array
     * @throws JsonException
     */
    protected function getCacheUser(int $userId): array
    {
        $value = RedisCache::hGet(UserCacheConstant::USER_CACHE_USER_INFO, $userId);
        if (!empty($value) && is_string($value) && is_json($value)) {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }

        return $value ?: [];
    }

    /**
     * @param  int  $roleId
     * @return array|mixed
     * @throws JsonException
     */
    protected function getCacheMenu(int $roleId)
    {
        $value = RedisCache::hGet(SystemCacheConstant::SYSTEM_CACHE_ROLE_PERMISSION, $roleId);
        if (!empty($value) && is_string($value) && is_json($value)) {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }

        return $value ?: [];
    }

    /**
     * @param  string  $appKey
     * @return array
     * @throws JsonException
     */
    protected function getAppCache(string $appKey): array
    {
        $value = RedisCache::hGet(SystemCacheConstant::SYSTEM_CACHE_APP_SYSTEM, $appKey);
        if (!empty($value) && is_string($value) && is_json($value)) {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }

        return $value ?: [];
    }
}
