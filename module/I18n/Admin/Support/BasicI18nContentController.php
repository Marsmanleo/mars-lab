<?php


namespace Module\I18n\Admin\Support;


use Illuminate\Routing\Controller;
use ModStart\Admin\Concern\HasAdminQuickCRUD;
use ModStart\Admin\Layout\AdminConfigBuilder;
use ModStart\Admin\Layout\AdminCRUDBuilder;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\CRUDUtil;
use ModStart\Field\AbstractField;
use ModStart\Field\AutoRenderedFieldValue;
use ModStart\Form\Form;
use ModStart\Grid\GridFilter;
use ModStart\Support\Concern\HasFields;
use Module\I18n\Type\LangType;

/**
 * Class BasicI18nContentController
 * @package Module\I18n\Admin\Support
 * @deprecated delete at 2023-12-28
 * use AbstractI18nContentController instead
 */
class BasicI18nContentController extends Controller
{
    use HasAdminQuickCRUD;

    public $configTitle = '单页面';
    public $configTable = 'table';
    public $configBaseUrl = '';

    protected function crud(AdminCRUDBuilder $builder)
    {
        $builder
            ->init($this->configTable)
            ->field(function ($builder) {
                /** @var HasFields $builder */
                $builder->display('id', 'ID')->width(80);
                $builder->display('_content', '内容')->hookRendering(function (AbstractField $field, $item, $index) {
                    $records = ModelUtil::all($this->configTable . '_data', ['dataId' => $item->id]);
                    $recordMap = array_build($records, function ($k, $v) {
                        return [$v['lang'], $v];
                    });
                    return AutoRenderedFieldValue::makeView('module::I18n.View.admin.i18n.gridItems', [
                        'item' => $item,
                        'langList' => LangType::getList(),
                        'recordMap' => $recordMap,
                        'urlBase' => action($this->registerGridClass . '@index'),
                    ]);
                });
            })
            ->gridFilter(function (GridFilter $filter) {
                $filter->eq('id', 'ID');
            })
            ->title($this->configTitle)
            ->disableCUD()->canShow(false)->canAdd(true);
    }

    public function edit()
    {
        return $this->doAddEdit(CRUDUtil::id());
    }

    public function add()
    {
        return $this->doAddEdit(CRUDUtil::id());
    }

    public function doAddEdit($id)
    {
        $input = InputPackage::buildFromInput();
        $record = false;
        if ($id) {
            $record = ModelUtil::get($this->configTable . '_data', $id);
            BizException::throwsIfEmpty('记录不存在', $record);
        }
        /** @var AdminConfigBuilder $builder */
        $builder = app(AdminConfigBuilder::class);
        $builder->useDialog();
        $builder->pageTitle($id ? '编辑' : '新增');
        $langValue = $input->getType('lang', LangType::class, null);
        $langField = $builder->type('lang', '语言')->type(LangType::class);
        if ($langValue) {
            $langField->defaultValue($langValue);
        }
        if ($id || $langValue) {
            $langField->readonly(true);
        }
        $this->crudField($builder);

        return $builder->perform($record, function (Form $form) use ($id, $input) {
            $data = $form->dataForming();
            ModelUtil::transactionBegin();
            if ($id) {
                ModelUtil::update($this->configTable . '_data', $id, $data);
            } else {
                $data['dataId'] = $input->getInteger('dataId');
                if (empty($data['dataId'])) {
                    $record = ModelUtil::insert($this->configTable, []);
                    $data['dataId'] = $record['id'];
                }
                ModelUtil::insert($this->configTable . '_data', $data);
            }
            ModelUtil::transactionCommit();
            return Response::redirect(CRUDUtil::jsDialogCloseAndParentGridRefresh());
        });
    }

    public function delete()
    {
        $id = CRUDUtil::id();
        $data = ModelUtil::get($this->configTable . '_data', $id);
        ModelUtil::transactionBegin();
        ModelUtil::delete($this->configTable . '_data', $id);
        $lefts = ModelUtil::all($this->configTable . '_data', ['dataId' => $data['dataId']]);
        if (empty($lefts)) {
            ModelUtil::delete($this->configTable, $data['dataId']);
        }
        ModelUtil::transactionCommit();
        return Response::redirect(CRUDUtil::jsGridRefresh());
    }

}
