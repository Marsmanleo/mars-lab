<?php


namespace Module\I18n\Traits;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Module\I18n\Util\LangUtil;

/**
 * Trait LangSupportTrait
 * @package Module\I18n\Traits
 * @deprecated delete at 2024-02-15
 */
trait LangSupportTrait
{
    private function langSetup()
    {
        $langShortName = Request::route('lang');
        $lang = null;
        if (!empty($langShortName)) {
            $lang = LangUtil::getByShortName($langShortName);
        }
        if (empty($lang)) {
            $lang = LangUtil::getDefault();
        }
        if (empty($lang)) {
            exit('ERR: Missing default language');
        }
        Session::flash('_lang', $lang);
        Session::flash('_locale', $lang['shortName']);
        View::share('_lang', $lang);
        View::share('_langShortName', $lang['shortName']);
        View::share('_langs', LangUtil::listAll());
    }
}
