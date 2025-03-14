<?php


namespace Module\I18n\Traits;

use ModStart\Core\Dao\ModelUtil;
use Module\I18n\Util\LangUtil;

trait LangModelSupportTrait
{
    /**
     * @param $key string|null name|shortName|image
     * @return string|array|null
     */
    public static function lang($key = null)
    {
        $lang = LangUtil::currentLang();
        return $key ? $lang[$key] : $lang;
    }

    public static function locale()
    {
        $lang = self::lang();
        return $lang['shortName'];
    }

    /**
     * @return mixed
     * @deprecated delete at 2024-02-15 use locale() instead
     */
    public static function langShortName()
    {
        $lang = self::lang();
        return $lang['shortName'];
    }

    public static function mergeLangParam($param = [])
    {
        return array_merge(['lang' => self::langShortName()], $param);
    }

    public static function get($id)
    {
        $record = ModelUtil::get(self::MODEL, $id);
        if (empty($record)) {
            return null;
        }
        $recordData = ModelUtil::get(self::MODEL . '_data', [
            'lang' => self::locale(),
            'dataId' => $record['id'],
        ]);
        if (empty($recordData)) {
            return null;
        }
        foreach ($record as $k => $v) {
            if (in_array($k, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }
            $recordData[$k] = $v;
        }
        return $recordData;
    }

    public static function listAll($lang = null, $where = [], $fields = ['*'], $sort = null)
    {
        $records = ModelUtil::all(
            self::MODEL . '_data',
            array_merge(['lang' => $lang ? $lang : self::locale()], $where),
            $fields,
            $sort
        );
        ModelUtil::join($records, 'dataId', '_main', self::MODEL, 'id');
        foreach ($records as $k => $v) {
            if (empty($v['_main'])) {
                continue;
            }
            foreach ($v['_main'] as $kk => $vv) {
                if (in_array($kk, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    continue;
                }
                $records[$k][$kk] = $vv;
            }
            unset($records[$k]['_main']);
        }
        return $records;
    }
}
