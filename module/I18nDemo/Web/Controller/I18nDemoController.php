<?php


namespace Module\I18nDemo\Web\Controller;


use ModStart\Core\Input\Response;
use Module\I18nDemo\Util\I18nDemoCategoryUtil;
use Module\I18nDemo\Util\I18nDemoUtil;

class I18nDemoController extends BaseController
{
    public function index($lang)
    {
        return $this->view('i18n.demo.index', [
            'categories' => I18nDemoCategoryUtil::listAll(),
        ]);
    }

    public function show($lang, $id)
    {
        $record = I18nDemoUtil::get($id);
        if (empty($record)) {
            return Response::page404();
        }
        return $this->view('i18n.demo.show', [
            'record' => $record
        ]);
    }
}
