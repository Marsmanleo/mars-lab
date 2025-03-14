<?php


namespace Module\I18n\Util;

use Module\I18n\Traits\LangModelSupportTrait;

/**
 * Class ConfigUtil
 * @package Module\I18n\Util
 * @deprecated delete at 2023-12-28
 */
class ConfigUtil
{
    use LangModelSupportTrait;

    public static function get($key, $defaultValue = null)
    {
        return modstart_config($key . '_' . self::langShortName(), $defaultValue);
    }
}
