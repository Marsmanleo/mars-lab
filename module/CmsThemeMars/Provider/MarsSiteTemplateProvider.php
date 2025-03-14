<?php


namespace Module\CmsThemeMars\Provider;


use Module\Vendor\Provider\SiteTemplate\AbstractSiteTemplateProvider;

class MarsSiteTemplateProvider extends AbstractSiteTemplateProvider
{
    const NAME = 'mars';

    public function name()
    {
        return self::NAME;
    }

    public function title()
    {
        return '主题Mars';
    }

    public function root()
    {
        return 'module::CmsThemeMars.View';
    }

}
