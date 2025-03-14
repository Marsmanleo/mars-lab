<?php


namespace Module\I18nNav\View;


use Illuminate\Support\Facades\View;

class I18nNavView
{
    public static function position($position)
    {
        return View::make('module::I18nNav.View.inc.nav', [
            'position' => $position,
        ])->render();
    }
}
