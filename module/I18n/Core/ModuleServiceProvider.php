<?php

namespace Module\I18n\Core;

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
                                    'title' => '多语言管理',
                                    'url' => '\Module\I18n\Admin\Controller\LangController@index',
                                ],
                                [
                                    'title' => '多语言翻译',
                                    'url' => '\Module\I18n\Admin\Controller\LangTransController@index',
                                ],
                            ]
                        ],
                    ]
                ],
            ];
        });

        ModuleClassLoader::addClass('I18n', __DIR__ . '/I18n.php');

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
