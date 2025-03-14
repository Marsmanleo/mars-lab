<?php


namespace Module\I18n\View;


use Illuminate\Support\Facades\View;

class I18nView
{
    public static function linkHrefLang($url)
    {
        return View::make('module::I18n.View.inc.linkHrefLang', [
            'url' => $url
        ]);
    }
}
