<?php

namespace Module\CmsThemeMars\Core;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use ModStart\Admin\Config\AdminMenu;
use Module\Cms\Provider\Theme\CmsThemeProvider;
use Module\CmsThemeMars\Provider\MarsThemeProvider;
use Module\Vendor\Provider\SiteTemplate\SiteTemplateProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        SiteTemplateProvider::register(ThemeSiteTemplateProvider::class);
        CmsThemeProvider::register(MarsThemeProvider::class);
        AdminMenu::register(function(){
            return [
               [
                   'title' => '功能设置',
                   'icon' => 'tools',
                   'sort' => 300,
                   'children' => [
                       [
                           'title' => '模板设置',
                           'children' => [
                               [
                                   'title' => 'Mars主题',
                                   'url' => '\Module\CmsThemeMars\Admin\Controller\ConfigController@index',
                               ],
                           ],
                       ],
                   ]
               ],
           ];
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
