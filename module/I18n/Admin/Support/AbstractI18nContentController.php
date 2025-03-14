<?php


namespace Module\I18n\Admin\Support;


use Illuminate\Routing\Controller;
use ModStart\Admin\Concern\HasAdminCRUD;
use ModStart\Admin\Layout\AdminDialogPage;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\CRUDUtil;
use ModStart\Core\Util\TreeUtil;
use ModStart\Field\AbstractField;
use ModStart\Field\AutoRenderedFieldValue;
use ModStart\Form\Form;
use ModStart\Grid\Grid;
use ModStart\ModStart;
use Module\I18n\Type\LangType;

abstract class AbstractI18nContentController extends Controller
{
    use HasAdminCRUD;

    private $config = [
        'title' => '内容',
        'table' => 'i18n_content',
        'tree' => false,
        'treeMaxLevel' => 3,
        'gridItemRender' => null,
    ];

    public function __construct()
    {
        $this->registerGridClass = '\\' . static::class;
    }

    /**
     * @param $config array
     */
    protected function config($config)
    {
        $this->config = array_merge($this->config, $config);
    }

    private function getPidOption()
    {
        $records = ModelUtil::all($this->config['table'], [], ['id', 'pid', 'sort']);
        $recordMap = [];
        foreach ($records as $record) {
            $recordMap[$record['id']] = $record;
        }
        $dataRecords = ModelUtil::all($this->config['table'] . '_data', [], ['id', 'dataId', 'title', 'lang']);
        foreach ($dataRecords as $dataRecord) {
            $recordMap[$dataRecord['dataId']]['_title'][$dataRecord['lang']] = $dataRecord['title'];
        }
        foreach ($recordMap as $k => $v) {
            $v['title'] = join(' | ', $v['_title']);
            unset($v['_title']);
            $recordMap[$k] = $v;
        }
        $tree = TreeUtil::nodesToTree($recordMap);
        $option = TreeUtil::treeToListWithIndent($tree, 'id', 'title');
        array_unshift($option, ['id' => 0, 'title' => L('Root')]);
        return $option;
    }

    protected function grid()
    {
        $this->getPidOption();
        $grid = Grid::make($this->config['table']);
        $grid->display('id', 'ID')->width(80);
        if ($this->config['tree']) {
            $grid->display('sort', '排序')->width(80);
            ModStart::style('td[data-field="_content"] .layui-table-cell { display: flex; align-items: center; }');
        }
        $grid->display('_content', '内容')
            ->hookRendering(function (AbstractField $field, $item, $index) {
                $records = ModelUtil::all($this->config['table'] . '_data', ['dataId' => $item->id]);
                $recordMap = array_build($records, function ($k, $v) {
                    return [$v['lang'], $v];
                });
                if ($this->config['gridItemRender']) {
                    foreach ($recordMap as $k => $v) {
                        $recordMap[$k]['_gridItemRender'] = call_user_func_array($this->config['gridItemRender'], [
                            $item->toArray(),
                            $v
                        ]);
                    }
                }
                return AutoRenderedFieldValue::makeView('module::I18n.View.admin.i18n.gridItems', [
                    'item' => $item,
                    'langList' => LangType::getList(),
                    'recordMap' => $recordMap,
                    'urlBase' => action($this->registerGridClass . '@index'),
                ]);
            });
        if ($this->config['tree']) {
            $grid->asTree('id', 'pid', 'sort', '_content');
            $grid->treeMaxLevel($this->config['treeMaxLevel']);
        }
        $grid->title($this->config['title']);
        $grid->canShow(false)->canDelete(false)->canEdit(false);
        return $grid;
    }

    abstract public function formField($builder);

    public function edit()
    {
        return $this->doAddEdit(CRUDUtil::id());
    }

    public function add()
    {
        return $this->doAddEdit(CRUDUtil::id());
    }

    protected function doAddEdit($id)
    {
        $input = InputPackage::buildFromInput();
        $record = null;
        $recordMain = null;
        if ($id) {
            $record = ModelUtil::get($this->config['table'] . '_data', $id);
            BizException::throwsIfEmpty('记录不存在', $record);
            $recordMain = ModelUtil::get($this->config['table'], $record['dataId']);
            BizException::throwsIfEmpty('记录不存在', $recordMain);
        }
        $form = Form::make('');
        $form->showSubmit(false)->showReset(false);

        if ($this->config['tree']) {
            $form->select('pid', '父级')->optionArray($this->getPidOption())->required();
            $form->number('sort', '排序')->defaultValue(ModelUtil::sortNext($this->config['table']));
        }

        $langValue = $input->getType('lang', LangType::class, null);
        $langField = $form->type('lang', '语言')->type(LangType::class);
        if ($langValue) {
            $langField->defaultValue($langValue);
        }
        if ($record || $langValue) {
            $langField->readonly(true);
        }

        $this->formField($form);
        if ($record) {
            if ($this->config['tree']) {
                $record['pid'] = $recordMain['pid'];
                $record['sort'] = $recordMain['sort'];
            }
            $form->item($record)->fillFields();
        }
        /** @var AdminDialogPage $page */
        $page = app(AdminDialogPage::class);
        return $page->body($form)
            ->pageTitle($record ? '编辑' : '添加')
            ->handleForm($form, function (Form $form) use ($record, $recordMain, $input) {
                $data = $form->dataForming();
                $dataMain = [];
                if ($this->config['tree']) {
                    $dataMain['pid'] = $data['pid'];
                    $dataMain['sort'] = $data['sort'];
                    unset($data['pid'], $data['sort']);
                    if ($recordMain && !TreeUtil::modelNodeChangeAble(
                            $this->config['table'],
                            $recordMain['id'],
                            $recordMain['pid'],
                            $dataMain['pid']
                        )) {
                        BizException::throws('不允许移动到该节点下');
                    }
                }
                ModelUtil::transactionBegin();
                if ($record) {
                    // 更新，只更新data表数据即可
                    if (!empty($dataMain)) {
                        ModelUtil::update($this->config['table'], $record['dataId'], $dataMain);
                    }
                    ModelUtil::update($this->config['table'] . '_data', $record['id'], $data);
                } else {
                    $data['dataId'] = $input->getInteger('dataId');
                    if (empty($data['dataId'])) {
                        $dataMain = ModelUtil::insert($this->config['table'], $dataMain);
                        $data['dataId'] = $dataMain['id'];
                    }
                    ModelUtil::insert($this->config['table'] . '_data', $data);
                }
                ModelUtil::transactionCommit();
                return Response::redirect(CRUDUtil::jsDialogCloseAndParentGridRefresh());
            });
    }

    public function delete()
    {
        $id = CRUDUtil::id();
        $data = ModelUtil::get($this->config['table'] . '_data', $id);
        ModelUtil::transactionBegin();
        ModelUtil::delete($this->config['table'] . '_data', $id);
        $lefts = ModelUtil::all($this->config['table'] . '_data', ['dataId' => $data['dataId']]);
        if (empty($lefts)) {
            ModelUtil::delete($this->config['table'], $data['dataId']);
        }
        ModelUtil::transactionCommit();
        return Response::redirect(CRUDUtil::jsGridRefresh());
    }

}
