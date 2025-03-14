<?php


namespace Module\I18nDemo\Admin\Controller;


use Module\I18n\Admin\Support\AbstractI18nContentController;

class I18nDemoCategoryController extends AbstractI18nContentController
{
    public function __construct()
    {
        parent::__construct();
        $this->config([
            'title' => '产品分类',
            'table' => 'i18n_demo_category',
        ]);
    }

    public function formField($builder)
    {
        $builder->number('sort', '排序')->defaultValue(999);
        $builder->text('title', '标题')->required();
        $builder->image('cover', '封面')->required();
        $builder->textarea('desc', '描述')->required();
    }

}
