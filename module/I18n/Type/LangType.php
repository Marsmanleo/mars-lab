<?php


namespace Module\I18n\Type;

use ModStart\Core\Type\BaseType;
use Module\I18n\Util\LangUtil;

class LangType implements BaseType
{
    public static function getList()
    {
        $result = [];
        foreach (LangUtil::listAll() as $lang) {
            $result[$lang['shortName']] = $lang['shortName'] . '-' . $lang['name'];
        }
        return $result;
    }

}
