<?php

namespace Module\CmsThemeMars\Admin\Controller;

use Illuminate\Routing\Controller;
use ModStart\Admin\Layout\AdminConfigBuilder;

class ConfigController extends Controller
{
    public function index(AdminConfigBuilder $builder)
    {
        $builder->pageTitle('Mars主题');
        $builder->text('CmsThemeMars_Test','测试变量配置');
        $builder->formClass('wide');
        return $builder->perform();
    }

}
