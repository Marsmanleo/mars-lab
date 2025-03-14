<?php


namespace Module\I18nDemo\Admin\Controller;


use Illuminate\Routing\Controller;
use ModStart\Admin\Layout\AdminConfigBuilder;
use ModStart\Core\Util\RenderUtil;
use Module\I18n\Admin\Support\HasAdminLangParam;

class ConfigController extends Controller
{
    use HasAdminLangParam;

    public function index(AdminConfigBuilder $builder)
    {
        $this->prepareLang();
        $this->prepareLangBuilder($builder);
        $builder->formClass('wide-lg');

        $builder->pageTitle('多语言站点设置');
        $builder->image($this->keyWithLangShortName('siteLogo'), $this->nameWithLangName('网站Logo'))
            ->help("调用 <code>\\I18n::config('siteLogo')</code>");
        $builder->text($this->keyWithLangShortName('siteName'), $this->nameWithLangName('网站名称'))
            ->help("调用 <code>\\I18n::config('siteName')</code>");
        $builder->text($this->keyWithLangShortName('siteSlogan'), $this->nameWithLangName('网站副标题'))
            ->help("调用 <code>\\I18n::config('siteSlogan')</code>");
        $builder->text($this->keyWithLangShortName('siteKeywords'), $this->nameWithLangName('网站关键词'))
            ->help("调用 <code>\\I18n::config('siteKeywords')</code>");
        $builder->textarea($this->keyWithLangShortName('siteDescription'), $this->nameWithLangName('网站描述'))
            ->help("调用 <code>\\I18n::config('siteDescription')</code>");

        return $builder->perform();
    }
}
