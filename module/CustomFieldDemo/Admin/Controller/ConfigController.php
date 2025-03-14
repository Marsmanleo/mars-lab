<?php

namespace Module\CustomFieldDemo\Admin\Controller;

use Illuminate\Routing\Controller;
use ModStart\Admin\Layout\AdminConfigBuilder;

class ConfigController extends Controller
{
    public function index(AdminConfigBuilder $builder)
    {
        $builder->pageTitle('自定义字段示例');
        $builder->text('CustomFieldDemo_Text', '普通字段');
        $builder->customFieldDemoField1('CustomFieldDemo_Field1', '自定义字段');
        return $builder->perform();
    }

}
