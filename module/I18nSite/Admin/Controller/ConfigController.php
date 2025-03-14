<?php

namespace Module\I18nSite\Admin\Controller;

use Illuminate\Routing\Controller;
use ModStart\Admin\Layout\AdminConfigBuilder;
use Module\I18n\Admin\Support\HasAdminLangParam;
use Module\Vendor\Provider\SiteTemplate\SiteTemplateProvider;

class ConfigController extends Controller
{
    use HasAdminLangParam;

    public function setting(AdminConfigBuilder $builder)
    {
        $builder->pageTitle('基本设置');

        $this->prepareLang();
        $this->prepareLangBuilder($builder);
        $builder->formClass('wide-lg');

        $builder->layoutSeparator('网站信息');
        $builder->image($this->keyWithLangShortName('siteLogo'), $this->nameWithLangName('网站Logo'));
        $builder->text($this->keyWithLangShortName('siteName'), $this->nameWithLangName('网站名称'));
        $builder->text($this->keyWithLangShortName('siteSlogan'), $this->nameWithLangName('网站副标题'));
        $builder->text('siteDomain', '网站域名')->help('如 example.com');
        $builder->text('siteUrl', '网站地址')->help('如 https://example.com 主要用于后台任务地址转换');
        $builder->text($this->keyWithLangShortName('siteKeywords'), $this->nameWithLangName('网站关键词'));
        $builder->textarea($this->keyWithLangShortName('siteDescription'), $this->nameWithLangName('网站描述'));
        $builder->image('siteFavIco', '网站ICO');

        $builder->layoutSeparator('模板主题');
        $builder->color('sitePrimaryColor', '网站主色调');
        $builder->select('siteTemplate', '网站模板')->options(SiteTemplateProvider::map());

        $builder->layoutSeparator('备案信息');
        $builder->text('siteBeian', 'ICP备案编号');
        $builder->text('siteBeianGonganText', '公安备案文字');
        $builder->text('siteBeianGonganLink', '公安备案链接');
        $builder->textarea($this->keyWithLangShortName('Site_CopyrightOthers'), $this->nameWithLangName('其他备案信息'))->help('支持HTML');

        $builder->layoutSeparator('联系信息');
        $builder->text('Site_ContactEmail', '邮箱');
        $builder->text('Site_ContactPhone', '电话');
        $builder->text($this->keyWithLangShortName('Site_ContactAddress'), $this->nameWithLangName('地址'));
        $builder->image('Site_ContactQrcode', '联系二维码')->help('可传带二维码的公众号/微信/QQ等，方便用户扫码联系');

        return $builder->perform();
    }

}
