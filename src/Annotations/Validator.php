<?php

namespace DDDCore\Annotations;

class Validator
{
    /** @var string 字段验证规则 */
    public string $rule;
    /** @var string 错误信息 */
    public string $message;
    /** @var string 字段名称 */
    public string $attribute;
}
