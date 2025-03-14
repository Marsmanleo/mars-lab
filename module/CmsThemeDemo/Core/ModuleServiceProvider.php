<?php

namespace Module\CmsThemeDemo\Core;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use ModStart\Admin\Config\AdminMenu;
use Module\Cms\Provider\Theme\CmsThemeProvider;
use Module\CmsThemeDemo\Provider\MarsSiteTemplateProvider;
use Module\CmsThemeDemo\Provider\MarsThemeProvider;
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
        SiteTemplateProvider::register(MarsSiteTemplateProvider::class);
        CmsThemeProvider::register(MarsThemeProvider::class);
        AdminMenu::register(function () {
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
                                    'title' => 'CMS开发演示模板',
                                    'url' => '\Module\CmsThemeDemo\Admin\Controller\ConfigController@basic',
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
