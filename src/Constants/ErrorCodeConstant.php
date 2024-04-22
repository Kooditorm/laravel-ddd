<?php

namespace DDDCore\Constants;

use BenSampo\Enum\Enum;

class ErrorCodeConstant extends Enum
{

    public const  NO_PERMISSION    = ['403' => '对不起，您没有权限访问']; //无权限访问
    public const  NO_FOUND         = ['404' => '资源不存在']; //资源不存在
    public const  NO_ACCEPT        = ['406' => '请求被拒绝'];//拒绝请求
    public const  OPTIONS_FREQUENT = ['405' => '操作频繁']; //操作频繁
    public const  SERVER_ERROR     = ['500' => '内部服务器错误']; //内部服务器错误
    public const  SYSTEM_ERROR     = ['501' => '系统内部异常, 请联系管理员']; //系统内部异常

}
