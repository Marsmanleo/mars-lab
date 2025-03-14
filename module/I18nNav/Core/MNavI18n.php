<?php

class MNavI18n
{
    public static function all($position = 'head')
    {
        $records = \Module\I18nNav\Util\NavUtil::listByPositionWithCache($position);
        foreach ($records as $i => $v) {
            $records[$i]['_attr'] = \Module\I18nNav\Type\NavOpenType::getBlankAttributeFromValue($v);
        }
        return $records;
    }
}
