<?php


namespace Module\I18n\Util;


use Illuminate\Support\Facades\Cache;
use ModStart\Core\Dao\ModelUtil;

class LangTransUtil
{
    const CACHE_KEY_MAP = 'I18n:LangTransMap';

    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY_MAP);
    }

    public static function map()
    {
        return Cache::remember(self::CACHE_KEY_MAP, 60, function () {
            try {
                $records = ModelUtil::all('lang_trans', [], ['lang', 'name', 'trans']);
                $map = [];
                foreach ($records as $record) {
                    $map[$record['lang']][$record['name']] = $record['trans'];
                }
                return $map;
            } catch (\Exception $e) {
                return [];
            }
        });
    }
}
