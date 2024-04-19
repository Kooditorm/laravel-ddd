<?php

namespace DDDCore\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * 基于jwt的认证实体
 *
 * @class AuthEntity
 * @package DDDCore\Entities
 */
abstract class AuthEntity extends Authenticatable implements JWTSubject
{
    /**
     * 自定义jwt声明
     *
     * @var array
     */
    protected array $claims = [];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return $this->claims;
    }

    /**
     *
     * Set a key value data, containing any custom claims to be added to the JWT.
     *
     * @param  array  $claims
     * @return $this
     */
    public function setJWTCustomClaims(array $claims): self
    {
        $this->claims = $claims;
        return $this;
    }


}
