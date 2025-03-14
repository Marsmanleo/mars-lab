<?php


namespace Module\I18nDemo\Admin\Controller;

use ModStart\Core\Dao\ModelUtil;
use Module\I18n\Admin\Support\AbstractI18nContentController;

class I18nDemoController extends AbstractI18nContentController
{
    public function __construct()
    {
        parent::__construct();
        $this->config([
            'title' => '多语言模型',
            'table' => 'i18n_demo',
        ]);
    }

    private function getCategoryOptions()
    {
        $map = [];
        foreach (ModelUtil::all('i18n_demo_category_data') as $category) {
            if (empty($map[$category['dataId']])) {
                $map[$category['dataId']] = [];
            }
            $map[$category['dataId']][] = $category['title'];
        }
        return array_build($map, function ($k, $v) {
            return [$k, join(' / ', $v)];
        });
    }

    public function formField($builder)
    {
        $builder->select('categoryId', '分类')->options($this->getCategoryOptions())->required();
        $builder->number('sort', '排序')->defaultValue(999);
        $builder->text('title', '标题')->required();
        $builder->image('cover', '图片')->required();
        $builder->textarea('desc', '描述')->required();
        $builder->richHtml('content', '内容')->required();
    }

}
