<?php


namespace Module\CmsThemeDemo\Provider;


use Module\Vendor\Provider\SiteTemplate\AbstractSiteTemplateProvider;

class DemoSiteTemplateProvider extends AbstractSiteTemplateProvider
{
    const NAME = 'demo';

    public function name()
    {
        return self::NAME;
    }

    public function title()
    {
        return '主题Demo';
    }

    public function root()
    {
        return 'module::CmsThemeDemo.View';
    }

}
