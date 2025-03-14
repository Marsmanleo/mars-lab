<?php


namespace Module\I18n\Admin\Support;


use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use ModStart\Admin\Layout\AdminConfigBuilder;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\RenderUtil;
use Module\I18n\Util\LangUtil;

trait HasAdminLangParam
{
    protected function prepareLang()
    {
        $input = InputPackage::buildFromInput();
        $lang = $input->getTrimString('lang');
        $lang = LangUtil::getByShortName($lang);
        if (empty($lang)) {
            $lang = LangUtil::getDefault();
        }
        if (empty($lang)) {
            Response::quit(-1, '无法找到默认语言');
        }
        Session::flash('_lang', $lang);
        View::share('_lang', $lang);
        return $lang;
    }

    protected function prepareLangBuilder($builder)
    {
        /** @var AdminConfigBuilder $builder */
        $builder->pagePrepend(
            RenderUtil::view('module::I18n.View.admin.i18n.langSwitch', [
                'lang' => $this->langShortName()
            ])
        );
    }

    protected function lang()
    {
        $lang = Session::get('_lang');
        if (empty($lang)) {
            Response::quit(-1, '无法找到默认语言');
        }
        return $lang;
    }

    protected function langShortName()
    {
        $lang = $this->lang();
        return $lang['shortName'];
    }

    protected function langName()
    {
        $lang = $this->lang();
        return $lang['name'];
    }

    protected function keyWithLangShortName($key)
    {
        return $key . '_' . $this->langShortName();
    }

    protected function nameWithLangName($name)
    {
        $lang = $this->lang();
        return '[' . $lang['name'] . '] ' . $name;
    }
}
