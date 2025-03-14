<?php


namespace Module\I18nNav\Biz;


use Module\Vendor\Provider\BizTrait;

class NavPositionBiz
{
    use BizTrait;

    /**
     * @return AbstractNavPositionBiz[]
     */
    public static function all()
    {
        return self::listAll();
    }

    /**
     * @param $name
     * @return AbstractNavPositionBiz
     */
    public static function get($name)
    {
        return self::getByName($name);
    }
}
