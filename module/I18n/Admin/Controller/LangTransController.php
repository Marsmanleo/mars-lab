<?php


namespace Module\I18n\Admin\Controller;


use Illuminate\Routing\Controller;
use ModStart\Admin\Concern\HasAdminQuickCRUD;
use ModStart\Admin\Layout\AdminCRUDBuilder;
use ModStart\Form\Form;
use ModStart\Grid\GridFilter;
use ModStart\Support\Concern\HasFields;
use Module\I18n\Type\LangType;
use Module\I18n\Util\LangTransUtil;

class LangTransController extends Controller
{
    use HasAdminQuickCRUD;

    protected function crud(AdminCRUDBuilder $builder)
    {
        $builder
            ->init('lang_trans')
            ->field(function ($builder) {
                /** @var HasFields $builder */
                $builder->id('id', 'ID');
                $builder->select('lang', '语言')->optionType(LangType::class);
                $builder->text('name', '翻译Key')->help('如 What is your name');
                $builder->text('trans', '翻译文字')->help('如 你叫什么名字');
            })
            ->gridFilter(function (GridFilter $filter) {
                $filter->eq('id', L('ID'));
                $filter->eq('lang', '语言')->select(LangType::class);
            })
            ->defaultOrder(['name', 'asc'])
            ->title('多语言翻译')
            ->hookSaved(function (Form $form) {
                LangTransUtil::clearCache();
            });
    }
}
