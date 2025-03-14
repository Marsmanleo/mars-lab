<?php

use Module\I18n\Traits\LangModelSupportTrait;

class I18n
{
    use LangModelSupportTrait;

    public static function config($key, $defaultValue = null)
    {
        return modstart_config($key . '_' . self::locale(), $defaultValue);
    }

    public static function configAssetUrl($key, $defaultValue = null)
    {
        $value = self::config($key, $defaultValue);
        return \ModStart\Core\Assets\AssetsUtil::fixFull($value);
    }

    public static function url($url)
    {
        return \Module\I18n\Util\LangUtil::url($url);
    }
}
