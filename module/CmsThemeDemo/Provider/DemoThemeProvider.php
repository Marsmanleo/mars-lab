<?php


namespace Module\CmsThemeDemo\Provider;

use Module\Cms\Provider\Theme\AbstractThemeProvider;

class DemoThemeProvider extends AbstractThemeProvider
{
    public function name()
    {
        return 'CmsThemeDemo';
    }
}