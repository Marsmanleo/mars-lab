<?php


namespace Module\I18nDemo\Web\Controller;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use ModStart\Core\Input\Request;
use ModStart\Core\View\ResponsiveViewTrait;
use Module\I18n\Traits\LangSupportTrait;

class BaseController extends Controller
{
    use ResponsiveViewTrait;
    use LangSupportTrait;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->langSetup();
    }
}
