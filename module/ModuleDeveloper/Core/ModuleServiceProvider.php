<?php

namespace Module\ModuleDeveloper\Core;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use ModStart\Admin\Config\AdminMenu;
use ModStart\Module\ModuleClassLoader;
use Module\ModuleDeveloper\Command\ModuleDeveloperApiDocDumpCommand;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        $this->commands([
            ModuleDeveloperApiDocDumpCommand::class,
        ]);
        AdminMenu::register(function () {
            return [
                [
                    'title' => L('System Manage'),
                    'icon' => 'code-alt',
                    'sort' => 700,
                    'children' => [
                        [
                            'title' => '模块开发助手',
                            'rule' => 'ModuleDeveloperManage',
                            'url' => '\Module\ModuleDeveloper\Admin\Controller\ModuleDeveloperController@index',
                        ]
                    ]
                ]
            ];
        });

        ModuleClassLoader::addNamespace('Mpociot', __DIR__ . '/../SDK/reflection-docblock/src/Mpociot');

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
