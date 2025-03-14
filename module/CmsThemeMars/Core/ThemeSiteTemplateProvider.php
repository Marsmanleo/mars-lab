<?php


namespace Module\CmsThemeMars\Core;

use Module\Vendor\Provider\SiteTemplate\AbstractSiteTemplateProvider;

class ThemeSiteTemplateProvider extends AbstractSiteTemplateProvider
{
    const NAME = 'themeMars';

    public function name()
    {
        return self::NAME;
    }

    public function title()
    {
        return 'Mars主题';
    }

    public function root()
    {
        return 'module::CmsThemeMars.View';
    }

}
