<?php

namespace DDDCore\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

abstract class AuthEntity extends Authenticatable implements JWTSubject
{
    protected array $claims = [];
}
