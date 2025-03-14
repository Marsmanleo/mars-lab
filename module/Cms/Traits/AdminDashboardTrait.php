<?php


namespace Module\Cms\Traits;


use ModStart\Admin\Layout\AdminPage;
use ModStart\Admin\Widget\SecurityTooltipBox;
use ModStart\Layout\Row;
use Module\AdminManager\Widget\ServerInfoWidget;
use Module\Cms\Widget\CmsInfoWidget;
use Module\Vendor\Admin\Widget\AdminWidgetDashboard;
use ModStart\Admin\Auth\Admin;
trait AdminDashboardTrait
{
    public function dashboard()
    {
        /** @var AdminPage $page */
        $page = app(AdminPage::class);
        $page->pageTitle(L('Dashboard'));
        $page->row(new SecurityTooltipBox());
        $page->append(new Row(function (Row $row) {
            AdminWidgetDashboard::callIcon($row);
        }));
        AdminWidgetDashboard::call($page);
        if ( Admin::id() == 1) {
            $page->append(new CmsInfoWidget());
        }
        $page->append(new ServerInfoWidget());
        return $page;
    }
}
