<?php

namespace DDDCore\Constants;

use BenSampo\Enum\Enum;

/**
 * @class Constant
 * @package DDDCore\Constants
 */
class Constant extends Enum
{
    /** @var int 未启用 */
    public const UNUSED = 0;
    /** @var int 启用 */
    public const ENABLE = 1;
    /** @var int 禁用 */
    public const DISABLE = 2;
    /** @var int 隐藏 */
    public const HIDDEN = 1;
    /** @var int 显示 */
    public const SHOW = 2;
}
