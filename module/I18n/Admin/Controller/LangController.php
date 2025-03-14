<?php


namespace Module\I18n\Admin\Controller;


use Illuminate\Routing\Controller;
use ModStart\Admin\Concern\HasAdminQuickCRUD;
use ModStart\Admin\Layout\AdminCRUDBuilder;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Form\Form;
use ModStart\Grid\GridFilter;
use ModStart\Support\Concern\HasFields;
use Module\I18n\Util\LangUtil;

class LangController extends Controller
{
    use HasAdminQuickCRUD;

    protected function crud(AdminCRUDBuilder $builder)
    {
        $builder
            ->init('lang')
            ->field(function ($builder) {
                /** @var HasFields $builder */
                $builder->id('id', 'ID');
                $builder->text('shortName', '缩写')->width(80);
                $builder->text('name', '名称');
                $builder->image('image', '图标');
                $builder->switch('enable', '启用');
                $builder->switch('isDefault', '默认')->gridEditable(true);
                $builder->display('created_at', L('Created At'))->listable(false);
                $builder->display('updated_at', L('Updated At'))->listable(false);
            })
            ->gridFilter(function (GridFilter $filter) {
                $filter->eq('id', L('ID'));
                $filter->like('name', L('Name'));
            })
            ->enablePagination(false)
            ->defaultOrder(['sort', 'asc'])
            ->canSort(true)
            ->title('多语言管理')
            ->hookSaved(function (Form $form) {
                $item = $form->item();
                if ($item->isDefault) {
                    ModelUtil::updateAll('lang', ['isDefault' => false], ['id', '<>', $item->id]);
                }
                LangUtil::clearCache();
            });
    }
}
