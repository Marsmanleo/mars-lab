<?php


namespace Module\ModuleDeveloper\Admin\Controller;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use ModStart\Admin\Auth\AdminPermission;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\FileUtil;
use ModStart\Core\Util\HtmlUtil;
use ModStart\Module\ModuleManager;
use Module\ModuleDeveloper\Util\ModuleApiDocSwaggerUtil;
use Module\ModuleDeveloper\Util\ModuleDeveloperUtil;
use Module\ModuleDeveloper\Util\ModuleLocalUtil;
use Module\ModuleDeveloper\Util\ModuleUtilDocUtil;

class ModuleDeveloperController extends Controller
{

    public function index()
    {
        AdminPermission::permitCheck('ModuleDeveloperManage');
        return view('module::ModuleDeveloper.View.admin.moduleDeveloper.index');
    }

    public function all()
    {
        AdminPermission::permitCheck('ModuleDeveloperManage');
        $localModules = ModuleManager::listModules();
        $modules = [];
        $i = 0;
        foreach ($localModules as $module => $moduleInfo) {
            $info = ModuleManager::getModuleBasic($module);
            $item = [];
            $item['name'] = $module;
            $item['title'] = $info['title'];
            $item['version'] = $info['version'];
            $item['description'] = $info['description'];
            $item['_info'] = $info;
            $item['_isLocalDir'] = false;
            $item['_isEnable'] = modstart_module_enabled($module);
            $item['_isInstall'] = ModuleManager::isModuleInstalled($module);
            $modules[] = $item;
        }
        $moduleLocalDir = config('env.MS_MODULE_DEVELOPER_LOCAL_MODULE_DIR');
        if (!empty($moduleLocalDir)) {
            foreach (ModuleLocalUtil::listAll($moduleLocalDir) as $module => $moduleInfo) {
                $info = ModuleLocalUtil::getModuleBasic($moduleLocalDir, $module);
                $item = [];
                $item['name'] = $module;
                $item['title'] = $info['title'];
                $item['version'] = $info['version'];
                $item['description'] = $info['description'];
                $item['_info'] = $info;
                $item['_isLocalDir'] = true;
                $item['_isEnable'] = false;
                $item['_isInstall'] = false;
                $modules[] = $item;
            }
        }
        return Response::generateSuccessData([
            'modules' => $modules,
        ]);
    }

    private function doFinish($msgs)
    {
        return Response::generateSuccessData([
            'msg' => array_map(function ($item) {
                return '<i class="iconfont icon-hr"></i> ' . $item;
            }, $msgs),
            'finish' => true,
        ]);
    }

    private function doNext($command, $step, $msgs = [], $data = [])
    {
        $input = InputPackage::buildFromInput();
        $data = array_merge($input->getJsonAsInput('data')->all(), $data);
        return Response::generateSuccessData([
            'msg' => array_map(function ($item) {
                return '<i class="iconfont icon-hr"></i> ' . $item;
            }, $msgs),
            'command' => $command,
            'step' => $step,
            'data' => $data,
            'finish' => false,
        ]);
    }

    public function publishInfo()
    {
        AdminPermission::permitCheck('ModuleDeveloperManage');
        $input = InputPackage::buildFromInput();
        $token = $input->getTrimString('token');
        $step = $input->getTrimString('step');
        BizException::throwsIfEmpty('请先登录ModStartCMS账号', $token);
        $dataInput = $input->getJsonAsInput('data');
        $module = $dataInput->getTrimString('module');
        $version = $dataInput->getTrimString('version');
        $isLocalDir = $dataInput->getBoolean('isLocalDir');
        BizException::throwsIfEmpty('module为空', $module);
        BizException::throwsIfEmpty('version为空', $version);
        $sessionDataKey = "ModuleDeveloper_${module}_${version}";
        switch ($step) {
            case 'finishModule':
                return $this->doFinish([
                    '<span class="ub-text-success">更新完成，请 <a href="https://modstart.com" target="_blank">登录</a> 查看审核结果</span>',
                ]);
            case 'uploadModule':
                $sessionData = Session::get($sessionDataKey);
                $sessionDataInput = InputPackage::build($sessionData);
                $moduleContent = $sessionDataInput->getTrimString('moduleContent');
                $moduleDemo = $sessionDataInput->getTrimString('moduleDemo');
                $moduleDocs = $sessionDataInput->getTrimString('moduleDocs');
                $modulePreview = $sessionDataInput->getTrimString('modulePreview');
                $moduleMobilePreview = $sessionDataInput->getTrimString('moduleMobilePreview');
                $moduleConfig = $sessionDataInput->getArray('moduleConfig');
                $ret = ModuleDeveloperUtil::updateModuleInfo(
                    $token, $module, $version,
                    $moduleContent, $moduleDemo, $moduleDocs,
                    $modulePreview, $moduleMobilePreview,
                    $moduleConfig
                );
                BizException::throwsIfResponseError($ret);
                $msgs = array_map(function ($msg) {
                    return "<span class=\"ub-text-success\">$msg</span>";
                }, $ret['data']['msgs']);
                if (empty($msgs)) {
                    $msgs[] = '<span class="ub-text-success">没有任何更新</span>';
                }
                Session::forget($sessionDataKey);
                return $this->doNext('publish_info', 'finishModule', array_merge($msgs, [
                    '<span class="ub-text-success">资料更新完成</span>',
                ]));
            case 'publishPack':
                $moduleDir = null;
                if ($isLocalDir) {
                    $moduleDir = config('env.MS_MODULE_DEVELOPER_LOCAL_MODULE_DIR');
                }
                $ret = ModuleDeveloperUtil::getModuleInfo($module, $version, $moduleDir);
                // $docs = json_decode($ret['data']['moduleDocs'],true);
                // $docContent = $docs[0]['content'];
                // echo $docContent."\n\n";
                // $docContent = HtmlUtil::filter($docContent);
                // echo "====== after ======\n";
                // echo $docContent."\n\n";exit();
                BizException::throwsIfResponseError($ret);
                $moduleConfig = ModuleManager::getModuleBasic($module);
                // print_r($moduleConfig);exit();
                Session::put($sessionDataKey, [
                    'moduleContent' => $ret['data']['moduleContent'],
                    'moduleDemo' => $ret['data']['moduleDemo'],
                    'moduleDocs' => $ret['data']['moduleDocs'],
                    'modulePreview' => $ret['data']['modulePreview'],
                    'moduleMobilePreview' => $ret['data']['moduleMobilePreview'],
                    'moduleConfig' => $moduleConfig,
                ]);
                return $this->doNext('publish_info', 'uploadModule', [
                    '<span class="ub-text-success">准备资料完成</span>',
                    '<span class="ub-text-white">正在准备更新...</span>'
                ]);
            default:
                return $this->doNext('publish_info', 'publishPack', [
                    '<span class="ub-text-success">开始更新模块资料 ' . $module . ' V' . $version . '</span>',
                    '<span class="ub-text-white">开始收集模块信息...</span>'
                ]);
        }
    }

    public function publish()
    {
        AdminPermission::permitCheck('ModuleDeveloperManage');
        $input = InputPackage::buildFromInput();
        $token = $input->getTrimString('token');
        $step = $input->getTrimString('step');
        BizException::throwsIfEmpty('请先登录ModStartCMS账号', $token);
        $dataInput = $input->getJsonAsInput('data');
        $module = $dataInput->getTrimString('module');
        $version = $dataInput->getTrimString('version');
        BizException::throwsIfEmpty('module为空', $module);
        BizException::throwsIfEmpty('version为空', $version);
        $sessionDataKey = "ModuleDeveloper_${module}_${version}";
        switch ($step) {
            case 'finishModule':
                return $this->doFinish([
                    '<span class="ub-text-success">打包完成，请 <a href="https://modstart.com" target="_blank">登录</a> 查看审核结果</span>',
                ]);
            case 'uploadModule':
                $sessionData = Session::get($sessionDataKey);
                $sessionDataInput = InputPackage::build($sessionData);
                $package = $sessionDataInput->getTrimString('package');
                $releaseFeature = $sessionDataInput->getTrimString('releaseFeature');
                $releaseContent = $sessionDataInput->getTrimString('releaseContent');
                $moduleContent = $sessionDataInput->getTrimString('moduleContent');
                $moduleDemo = $sessionDataInput->getTrimString('moduleDemo');
                $moduleDocs = $sessionDataInput->getTrimString('moduleDocs');
                $modulePreview = $sessionDataInput->getTrimString('modulePreview');
                $moduleMobilePreview = $sessionDataInput->getTrimString('moduleMobilePreview');
                $apiContent = $sessionDataInput->getTrimString('apiContent');
                $utilContent = $sessionDataInput->getTrimString('utilContent');
                $moduleConfig = $sessionDataInput->getArray('moduleConfig');
                BizException::throwsIfEmpty('package为空', $package);
                BizException::throwsIfEmpty('releaseFeature为空', $releaseFeature);
                BizException::throwsIfEmpty('releaseContent为空', $releaseContent);
                $ret = ModuleDeveloperUtil::uploadModule(
                    $token, $module, $version, $package,
                    $releaseFeature, $releaseContent,
                    $moduleContent, $moduleDemo, $moduleDocs,
                    $modulePreview, $moduleMobilePreview, $apiContent, $utilContent,
                    $moduleConfig
                );
                BizException::throwsIfResponseError($ret);
                $msgs = array_map(function ($msg) {
                    return "<span class=\"ub-text-success\">$msg</span>";
                }, $ret['data']['msgs']);
                Session::forget($sessionDataKey);
                return $this->doNext('publish', 'finishModule', array_merge($msgs, [
                    '<span class="ub-text-success">文件上传完成</span>',
                    '<span class="ub-text-success">清理文件完成</span>',
                ]));
            case 'packModule':
                $ret = ModuleDeveloperUtil::packModule($module, $version);
                BizException::throwsIfResponseError($ret);
                $moduleConfig = ModuleManager::getModuleBasic($module);
                Session::put($sessionDataKey, [
                    'package' => $ret['data']['package'],
                    'releaseFeature' => $ret['data']['releaseFeature'],
                    'releaseContent' => $ret['data']['releaseContent'],
                    'moduleContent' => $ret['data']['moduleContent'],
                    'moduleDemo' => $ret['data']['moduleDemo'],
                    'moduleDocs' => $ret['data']['moduleDocs'],
                    'modulePreview' => $ret['data']['modulePreview'],
                    'moduleMobilePreview' => $ret['data']['moduleMobilePreview'],
                    'apiContent' => $ret['data']['apiContent'],
                    'utilContent' => $ret['data']['utilContent'],
                    'moduleConfig' => $moduleConfig,
                ]);
                return $this->doNext('publish', 'uploadModule', [
                    '<span class="ub-text-success">打包压缩包完成，文件' . $ret['data']['fileCount'] . '个，大小 '
                    . FileUtil::formatByte($ret['data']['fileSize']) . '</span>',
                    '<span class="ub-text-white">正在上传压缩包...</span>'
                ]);
            default:
                return $this->doNext('publish', 'packModule', [
                    '<span class="ub-text-success">开始发布远程模块 ' . $module . ' V' . $version . '</span>',
                    '<span class="ub-text-white">开始打包模块...</span>'
                ]);
        }
    }

    public function downloadZip()
    {
        AdminPermission::permitCheck('ModuleDeveloperManage');
        $input = InputPackage::buildFromInput();
        $module = $input->getTrimString('module');
        $version = $input->getTrimString('version');
        $moduleInfo = ModuleManager::getModuleBasic($module);
        BizException::throwsIfEmpty('没有找到模块', $moduleInfo);
        BizException::throwsIf('模块版本不匹配', $moduleInfo['version'] != $version);
        $ret = ModuleDeveloperUtil::packModule($module, $version);
        BizException::throwsIfResponseError($ret);
        $zipFile = $ret['data']['package'];
        return Response::download($module . '-v' . $version . '.zip', FileUtil::safeGetContent($zipFile));
    }

    public function apiDoc()
    {
        AdminPermission::permitCheck('ModuleDeveloperManage');
        $input = InputPackage::buildFromInput();
        $module = $input->getTrimString('module');
        $moduleInfo = ModuleManager::getModuleBasic($module);
        BizException::throwsIfEmpty('没有找到模块', $moduleInfo);
        $ret = ModuleApiDocSwaggerUtil::generate($module);
        BizException::throwsIfResponseError($ret);
        if (empty($ret['data']['json'])) {
            return Response::sendError('该模块没有接口文档');
        }
        return view('module::ModuleDeveloper.View.admin.moduleDeveloper.apiDoc', [
            'moduleInfo' => $moduleInfo,
            'spec' => $ret['data']['json'],
        ]);
    }

    public function utilDoc()
    {
        AdminPermission::permitCheck('ModuleDeveloperManage');
        $input = InputPackage::buildFromInput();
        $module = $input->getTrimString('module');
        $moduleInfo = ModuleManager::getModuleBasic($module);
        BizException::throwsIfEmpty('没有找到模块', $moduleInfo);
        $ret = ModuleUtilDocUtil::generate($module);
        BizException::throwsIfResponseError($ret);
        if (empty($ret['data']['json'])) {
            return Response::sendError('该模块没有接口文档');
        }
        return view('module::ModuleDeveloper.View.admin.moduleDeveloper.utilDoc', [
            'moduleInfo' => $moduleInfo,
            'utilContent' => $ret['data']['json'],
        ]);
    }

}
