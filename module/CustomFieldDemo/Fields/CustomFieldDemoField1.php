<?php


namespace Module\CustomFieldDemo\Fields;


use ModStart\Field\Image;

class CustomFieldDemoField1 extends Image
{
    protected $view = 'module::CustomFieldDemo.View.field.customField1';

    protected function setup()
    {
        parent::setup();
        $this->addVariables([
            'varA' => '变量A',
            'varB' => '变量B',
        ]);
    }

}
