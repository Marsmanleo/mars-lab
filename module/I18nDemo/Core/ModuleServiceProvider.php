<?php

namespace Module\I18nDemo\Core;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use ModStart\Admin\Config\AdminMenu;
use ModStart\Module\ModuleClassLoader;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        AdminMenu::register(function () {
            return [
                [
                    'title' => '内容管理',
                    'icon' => 'file',
                    'sort' => 150,
                    'children' => [
                        [
                            'title' => '多语言',
                            'children' => [
                                [
                                    'title' => '多语言站点设置',
                                    'url' => '\Module\I18nDemo\Admin\Controller\ConfigController@index',
                                ],
                                [
                                    'title' => '多语言分类（示例）',
                                    'url' => '\Module\I18nDemo\Admin\Controller\I18nDemoCategoryController@index',
                                ],
                                [
                                    'title' => '多语言模型（示例）',
                                    'url' => '\Module\I18nDemo\Admin\Controller\I18nDemoController@index',
                                ],
                            ]
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
