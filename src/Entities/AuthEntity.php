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
     * 定义表前缀
     *
     * @var string|null
     */
    protected ?string $prefix = null;

    /**
     * 自定义jwt声明
     *
     * @var array
     */
    protected array $claims = [];


    /**
     * @inheritDoc
     *
     */
    public function getTable(): string
    {
        if (!is_null($this->prefix)) {
            $this->getConnection()->setTablePrefix($this->prefix);
        }
        return parent::getTable();
    }


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
