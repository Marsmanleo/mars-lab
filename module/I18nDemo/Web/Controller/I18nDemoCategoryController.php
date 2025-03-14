<?php


namespace Module\I18nDemo\Web\Controller;

use ModStart\Core\Input\Response;
use Module\I18nDemo\Util\I18nDemoCategoryUtil;

class I18nDemoCategoryController extends BaseController
{
    public function index($lang, $id)
    {
        $category = I18nDemoCategoryUtil::get($id);
        if (empty($category)) {
            return Response::page404();
        }
        return $this->view('i18n.demo.category', [
            'category' => $category
        ]);
    }
}
