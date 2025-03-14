<?php


namespace Module\CmsThemeDemo\Admin\Controller;


use Illuminate\Routing\Controller;
use ModStart\Admin\Layout\AdminConfigBuilder;

class ConfigController extends Controller
{
    public function basic(AdminConfigBuilder $builder)
    {
        $builder->pageTitle('CMS开发演示模板');
        $builder->text('CmsThemeDemo_Foo', '配置A')->help('前端可使用 modstart_config(\'CmsThemeDemo_Foo\') 获取');
        $builder->formClass('wide');
        return $builder->perform();
    }
}
