<?php


namespace Module\I18n\Util;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use ModStart\Core\Assets\AssetsUtil;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Exception\BizException;

class LangUtil
{
    const CACHE_KEY = 'I18n:Lang';

    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
    }

    public static function getDefault($key = null)
    {
        $first = null;
        foreach (self::listAll() as $v) {
            if ($v['isDefault']) {
                $first = $v;
                break;
            }
        }
        if (empty($first) || empty($key)) {
            return $first;
        }
        return $first[$key];
    }

    public static function getByShortName($shortName)
    {
       foreach (self::listAll() as $v) {
            if ($v['shortName'] == $shortName) {
                return $v;
            }
        }
        return null;
    }

    public static function listAll()
    {
        return Cache::remember(self::CACHE_KEY, 60, function () {
            try {
                $records = ModelUtil::all('lang', ['enable' => true], ['*'], ['sort', 'asc']);
                AssetsUtil::recordsFixFullOrDefault($records, 'image');
                return $records;
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    public static function currentLang()
    {
        static $lang = null;
        if (is_null($lang)) {
            $lang = Session::get('_lang');
            if (empty($lang)) {
                $routeLocale = Request::route('locale');
                if (!empty($routeLocale)) {
                    $lang = LangUtil::getByShortName($routeLocale);
                }
                if (empty($lang)) {
                    $sessionLocale = Session::get('_locale', null);
                    if (!empty($sessionLocale)) {
                        $lang = LangUtil::getByShortName($sessionLocale);
                    }
                }
                if (empty($lang)) {
                    $lang = LangUtil::getDefault();
                }
                if (empty($lang)) {
                    $lang = [
                        'name' => 'English',
                        'shortName' => 'en',
                        'image' => null,
                        'enable' => true,
                        'isDefault' => true,
                    ];
                }
            }
        }
        return $lang;
    }

    public static function url($url, $lang = null)
    {
        if (empty($lang)) {
            $lang = self::currentLang();
        }
        return modstart_web_url($lang['shortName']) . ($url ? '/' . $url : '');
    }

    public static function currentPageUrlWithoutLang()
    {
        $url = \ModStart\Core\Input\Request::currentPageUrl();
        $search = '/' . L_locale() . '';
        var_dump($url);
        return $url;
    }


    public static function action($controller, $param = [])
    {
        $lang = self::currentLang();
        return action($controller, array_merge(['lang' => $lang['shortName']], $param));
    }
}
