<?php


namespace Module\ModuleDeveloper\Admin\Controller;


use Chumper\Zipper\Zipper;
use Illuminate\Routing\Controller;
use ModStart\Admin\Auth\AdminPermission;
use ModStart\Admin\Layout\AdminDialogPage;
use ModStart\Core\Dao\ModelManageUtil;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Request;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\FileUtil;
use ModStart\Core\Util\SerializeUtil;
use ModStart\Core\Util\StrUtil;
use ModStart\Form\Form;
use ModStart\ModStart;
use ModStart\Module\ModuleManager;
use ModStart\Widget\Box;
use Module\ModuleDeveloper\Util\ModuleApiDocSwaggerUtil;
use Module\ModuleDeveloper\Util\ModuleDeveloperUtil;
use Module\ModuleDeveloper\Util\ModuleTrashUtil;
use Module\Vendor\Markdown\MarkdownUtil;

class ModuleDeveloperToolsController extends Controller
{
    public static $PermitMethodMap = [
        '*' => '@ModuleDeveloperManage',
    ];

    public function modules(ModuleDeveloperController $api)
    {
        AdminPermission::permitCheck('ModuleDeveloperManage');
        return $api->all();
    }

    public function addModule()
    {
        AdminPermission::permitCheck('ModuleDeveloperManage');
        $input = InputPackage::buildFromInput();
        $data = [];
        $data['name'] = $input->getTrimString('name');
        $data['title'] = $input->getTrimString('title');
        $data['description'] = $input->getTrimString('description');
        $data['env'] = $input->getArray('env');
        BizException::throwsIfEmpty('模块标识为空', $data['name']);
        BizException::throwsIfEmpty('模块名称为空', $data['title']);
        BizException::throwsIf('模块标识必须首字母大写', !preg_match('/^[A-Z]/', $data['name']));
        BizException::throwsIfEmpty('环境为必选', $data['env']);

        $themeName = null;
        $themeModule = null;
        $type = $input->getTrimString('type');
        switch ($type) {
            case 'cmsTheme':
                $themeName = $type . ucfirst($data['name']);
                $data['name'] = 'CmsTheme' . $data['name'];
                $data['title'] = 'CMS' . $data['title'] . '主题';
                $from = base_path("module/ModuleDeveloper/Stub/CmsTheme");
                break;
            case 'blogTheme':
                $themeName = $type . ucfirst($data['name']);
                $data['name'] = 'BlogTheme' . $data['name'];
                $data['title'] = 'Blog' . $data['title'] . '主题';
                $from = base_path("module/ModuleDeveloper/Stub/BlogTheme");
                break;
            case 'theme':
                $themeModule = $input->getTrimString('module');
                BizException::throwsIfEmpty('模块为空', $themeModule);
                BizException::throwsIf('模块不存在', !ModuleManager::isExists($themeModule));
                $themeName = $type . ucfirst($data['name']);
                $data['name'] = $themeModule . 'Theme' . $data['name'];
                $data['title'] = $data['title'] . '主题';
                $from = base_path("module/ModuleDeveloper/Stub/Theme");
                break;
            case 'uniapp':
                $themeName = $type . ucfirst($data['name']);
                $data['name'] = $data['name'] . 'Mobile';
                $data['title'] = $data['title'] . '移动端';
                $from = base_path("module/ModuleDeveloper/Stub/Uniapp");
                break;
            default:
                $from = base_path("module/ModuleDeveloper/Stub/Example");
                break;
        }
        $moduleName = $data['name'];
        $moduleDir = base_path('module/' . $data['name']);
        BizException::throwsIf('模块已存在', file_exists($moduleDir));
        BizException::throwsIf('模板Stub文件不存在', !file_exists($from));
        $files = FileUtil::listAllFiles($from);
        $files = array_filter($files, function ($item) {
            return $item['isFile'];
        });
        BizException::throwsIfEmpty('模块创建失败（内容为空）', $files);

        $param = [];
        foreach ($data as $k => $v) {
            $param['$Module' . ucfirst($k) . '$'] = $v;
        }
        $param['$module_name$'] = StrUtil::uncamelize($data['name']);
        $param['$module_name_mobile_short$'] = $param['$module_name$'];
        if (ends_with($param['$module_name_mobile_short$'], '_mobile')) {
            $param['$module_name_mobile_short$'] = substr($param['$module_name_mobile_short$'], 0, -7);
        }
        $param['$MODULE_NAME$'] = strtoupper($param['$module_name$']);
        $param['$module-name$'] = str_replace('_', '-', $param['$module_name$']);
        $param['$moduleName$'] = lcfirst($data['name']);
        $param['$ModstartVersion$'] = ModStart::$version;
        $vendorBasic = ModuleManager::getModuleBasic('Vendor');
        $param['$VendorVersion$'] = $vendorBasic['version'];
        $param['$year_month_day$'] = date('Y_m_d');
        $param['$ThemeModule$'] = $themeModule;
        $param['$Thememodule$'] = lcfirst($themeModule);
        $param['$ThemeName$'] = $themeName;
        $param['$ModuleEnv$'] = SerializeUtil::jsonEncode($data['env']);
        $param['$CurrentDomainUrl$'] = Request::domainUrl();

        $search = array_keys($param);
        $replace = array_values($param);

        foreach ($files as $file) {
            $content = file_get_contents($file['pathname']);
            $content = str_replace($search, $replace, $content);

            $relativePath = $file['filename'];
            if ($file['ext'] == 'stub') {
                $relativePath = substr($relativePath, 0, -5);
            }
            $relativePath = str_replace($search, $replace, $relativePath);
            $path = join('/', [
                rtrim($moduleDir, '/'),
                $relativePath
            ]);
            FileUtil::write($path, $content);
            // echo "$path -> $content\n\n---------------------------------------\n";
        }

//        $ret = ModuleManager::install($moduleName);
//        BizException::throwsIfResponseError($ret);
//        $ret = ModuleManager::enable($moduleName);
//        BizException::throwsIfResponseError($ret);

        return Response::generateSuccess();
    }

    public function trashModule()
    {
        AdminPermission::permitCheck('ModuleDeveloperManage');
        $records = ModuleTrashUtil::listAll();
        return Response::generateSuccessData([
            'records' => $records
        ]);
    }

    public function trashModuleDelete()
    {
        AdminPermission::permitCheck('ModuleDeveloperManage');
        $input = InputPackage::buildFromInput();
        $filename = $input->getTrimString('filename');
        ModuleTrashUtil::clean($filename);
        return Response::generateSuccess();
    }

    public function allApi()
    {
        $ret = ModuleApiDocSwaggerUtil::generateApp();
        BizException::throwsIfResponseError($ret);
        if (empty($ret['data']['json'])) {
            return Response::sendError('该模块没有接口文档');
        }
        return view('module::ModuleDeveloper.View.admin.moduleDeveloper.allApiDoc', [
            'spec' => $ret['data']['json'],
            'title' => $ret['data']['title'],
        ]);
    }

    public function allDbStructure()
    {
        $markdown = ModelManageUtil::databaseStructureMarkdown();
        $markdownHtml = MarkdownUtil::convertToHtml($markdown);
        return view('module::ModuleDeveloper.View.admin.moduleDeveloper.allDbStructure', [
            'markdown' => $markdown,
            'markdownHtml' => $markdownHtml,
        ]);
    }

    public function packageFile(AdminDialogPage $page)
    {
        $input = InputPackage::buildFromInput();
        $token = $input->getTrimString('token');
        BizException::throwsIfEmpty('token不能为空', $token);
        $root = base_path('');
        if (function_exists('shell_exec')) {
            try {
                $commands = [
                    "cd $root && git diff --name-only --no-color 2>/dev/null",
                    "cd $root && git diff --staged --name-only --no-color 2>/dev/null",
                    "cd $root && git ls-files --others --exclude-standard 2>/dev/null",
                ];
                $changedFilesResult = [];
                foreach ($commands as $command) {
                    $changedFilesResult[] = trim(@shell_exec($command));
                }
                $changedFilesResult = join("\n", $changedFilesResult);
                $changedFilesResult = array_unique(array_filter(array_map('trim', explode("\n", $changedFilesResult))));
                $changedFiles = join("\n", $changedFilesResult);
            } catch (\Exception $e) {
            }
        }
        if (empty($changedFiles)) {
            $changedFiles = '';
        }
        $form = Form::make('');
        $form->text('fileName', '文件名称')
            ->required()
            ->defaultValue('更新说明_' . date('Ymd_His'));
        $form->textarea('files', '文件路径')
            ->required()
            ->autoHeight(true)
            ->placeholder("如 module/Xxx/a.txt\nmodule/Xxx/b.txt\n每行一个")
            ->defaultValue($changedFiles);
        $form->html('_result', '上传结果')
            ->htmlContent('<span class="ub-text-muted"><i class="iconfont icon-warning"></i> 提交后显示</span>');
        ModStart::js('asset/common/clipboard.js');
        ModStart::script('$(".panel-dialog-foot [data-submit]").remove();');
        return $page
            ->pageTitle('文件打包')
            ->body(Box::make($form, '文件打包'))
            ->handleForm($form, function (Form $form) use ($token) {
                $data = $form->dataForming();
                $files = array_values(array_filter(array_map('trim', explode("\n", $data['files']))));
                BizException::throwsIfEmpty('文件路径不能为空', $files);
                $zipTemp = FileUtil::generateLocalTempPath('zip');
                $zipper = new Zipper();
                $zipper->make($zipTemp);
                foreach ($files as $f) {
                    $path = base_path($f);
                    BizException::throwsIf('文件不存在：' . $f, !file_exists($path));
                    $zipper->add($path, 'wwwroot/' . $f);
                }
                $zipper->addString('readme.txt', '请解压后，按路径更新（不存在则创建）这些文件，wwwroot 表示网站根目录');
                $zipper->close();
                $zipContent = file_get_contents($zipTemp);
                FileUtil::safeCleanLocalTemp($zipTemp);

                $content = [];
                $content['files'] = [];
                $content['files']['updates'] = $files;
                $ret = ModuleDeveloperUtil::uploadHotfix(
                    $token, $data['fileName'], $content, $zipContent
                );
                BizException::throwsIfResponseError($ret);

                $text = $ret['data']['text'];
                $content = join('', [
                    "<div><pre>{$text}</pre></div>",
                    "<div><button type='button' class='btn btn-round btn-sm' data-clipboard-text='" . htmlspecialchars($text) . "'><i class='iconfont icon-copy'></i> 复制</button></div>",
                ]);
                return Response::generate(0, null, null,
                    '[js]$("[data-field=_result] .ub-html").html(' . SerializeUtil::jsonEncode($content) . ')');

                // $fileName = $data['fileName'] . '.zip';
                // return Response::download($fileName, $zipContent);
            });
    }
}
