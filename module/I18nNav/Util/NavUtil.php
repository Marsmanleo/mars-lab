<?php

namespace Module\I18nNav\Util;

use Illuminate\Support\Facades\Cache;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Util\TreeUtil;
use Module\I18n\Type\LangType;
use Module\I18nNav\Type\NavPosition;

class NavUtil
{
    const CACHE_KEY_PREFIX = 'nav:';

    public static function add($position, $name, $link)
    {
        $sort = intval(ModelUtil::max('nav_i18n', 'sort')) + 1;
        ModelUtil::insert('nav_i18n', [
            'position' => $position,
            'name' => $name,
            'link' => $link,
            'sort' => $sort,
        ]);
    }

    public static function tree()
    {
        $nodes = TreeUtil::modelToTree('nav_i18n', [
            'position' => 'position',
            'lang' => 'lang',
            'name' => 'name',
            'openType' => 'openType',
            'link' => 'link',
            'icon' => 'icon',
        ], 'id', 'pid', 'sort', ['enable' => true]);
        return $nodes;
    }

    /**
     * 根据位置获取
     *
     * @param $position string 位置
     * @param $locale string 语言
     * @return array
     */
    public static function listByPosition($position = 'header', $locale = null)
    {
        if (null === $locale) {
            $locale = L_locale();
        }
        $nodes = self::tree();
        return array_filter($nodes, function ($item) use ($position, $locale) {
            return $item['position'] == $position && $item['lang'] == $locale;
        });
    }

    /**
     * 根据位置获取，有缓存
     *
     * @param $position string 位置
     * @param $locale string 语言
     * @param $minutes int 缓存时间
     * @return array
     */
    public static function listByPositionWithCache($position = 'header', $locale = null, $minutes = 600)
    {
        if (null === $locale) {
            $locale = L_locale();
        }
        return Cache::remember(self::CACHE_KEY_PREFIX . $locale . ':' . $position, $minutes, function () use ($position, $locale) {
            return self::listByPosition($position, $locale);
        });
    }

    public static function hasData($position = 'header')
    {
        $values = self::listByPositionWithCache($position);
        return !empty($values);
    }

    public static function clearCache()
    {
        foreach (LangType::getList() as $l => $v) {
            foreach (NavPosition::getList() as $k => $_) {
                Cache::forget(self::CACHE_KEY_PREFIX . $l . ':' . $k);
            }
        }
    }

}
