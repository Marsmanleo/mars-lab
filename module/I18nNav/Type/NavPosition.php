<?php


namespace Module\I18nNav\Type;


use ModStart\Core\Type\BaseType;
use ModStart\Module\ModuleManager;
use Module\I18nNav\Biz\NavPositionBiz;

class NavPosition implements BaseType
{
    public static function getList()
    {
        return array_merge(
            ModuleManager::getModuleConfigKeyValueItems('I18nNav', 'position'),
            NavPositionBiz::allMap()
        );
    }

    public static function first()
    {
        $list = self::getList();
        $keys = array_keys($list);
        return array_shift($keys);
    }
}
