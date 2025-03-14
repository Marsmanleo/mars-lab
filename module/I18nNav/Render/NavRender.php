<?php


namespace Module\I18nNav\Render;


use Illuminate\Support\Facades\View;

/**
 * Class NavRender
 * @package Module\I18nNav\Render
 * @deprecated delete at 2023-04-07
 */
class NavRender
{
    public static function position($position)
    {
        return View::make('module::I18nNav.View.inc.nav', [
            'position' => $position,
        ])->render();
    }
}
