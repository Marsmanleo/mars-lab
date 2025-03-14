<?php

namespace Module\CustomFieldDemo\Core;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use ModStart\Admin\Config\AdminMenu;
use ModStart\Support\Manager\FieldManager;
use Module\CustomFieldDemo\Fields\CustomFieldDemoField1;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        FieldManager::extend('customFieldDemoField1', CustomFieldDemoField1::class);
        AdminMenu::register(function () {
            return [
                [
                    'title' => '功能设置',
                    'icon' => 'tools',
                    'sort' => 300,
                    'children' => [
                        [
                            'title' => '自定义字段示例',
                            'url' => '\Module\CustomFieldDemo\Admin\Controller\ConfigController@index',
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
