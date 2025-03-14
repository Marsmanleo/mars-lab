<?php


namespace Module\ModuleDeveloper\Util;


use ModStart\Core\Exception\BizException;
use ModStart\Core\Util\FileUtil;
use ModStart\Module\ModuleManager;
use Mpociot\Reflection\DocBlock;

class ModuleApiDocUtil
{
    /**
     * @param $module
     * @return array
     * @throws BizException
     * @throws \ReflectionException
     *
     * @doc 支持的注解
     *
     * 类：
     * @Api 分组
     *
     * 方法：
     * @Api 名称
     * @ApiDesc 接口说明
     * @ApiMethod post|get
     * @ApiDataType json|formData
     * @ApiHeadParam api-token string required 参数说明
     * @ApiBodyParam bizId int required 企业ID
     * @ApiQueryParam bizId int required 企业ID
     * @ApiResponseCode 10000 用户未登录
     * @ApiResponseData {
     *  "code":0,
     *  "data":{
     *      "foo":1,
     *      "bar":2
     *  }
     * }
     */
    public static function generate($module)
    {
        $apiRouteFile = ModuleManager::path($module, 'Api/routes.php');
        $apiRouteMap = [];
        if (file_exists($apiRouteFile)) {
            $mat = file_get_contents($apiRouteFile);
            preg_match_all('/[\'"]([a-zA-Z0-9_\\{\\}\\/]+)[\'"],\\s*[\'"]([A-Za-z0-9]+@[A-Za-z0-9]+)[\'"]\\)\\s*;/', $mat, $mat);
//            if ($module == 'Member') {
//                print_r($mat[1]);
//                exit();
//            }
            if (!empty($mat[1])) {
                foreach ($mat[1] as $i => $v) {
                    $apiRouteMap[$mat[2][$i]] = $v;
                }
            }
        }
        $fixedApiRouteMap = [];
        foreach ($apiRouteMap as $ca => $u) {
            if (!starts_with($ca, 'Module')) {
                $ca = '\\Module\\' . $module . '\\Api\\Controller\\' . $ca;
            }
            if (!starts_with($u, 'api')) {
                $u = modstart_api_url($u);
            }
            $fixedApiRouteMap[$ca] = $u;
        }
        $apiControllerDir = ModuleManager::path($module, 'Api/Controller');
        $files = array_filter(FileUtil::listAllFiles($apiControllerDir), function ($f) {
            return $f['ext'] == 'php';
        });
//        if ($module == 'Member') {
//            print_r($files);
//            print_r($fixedApiRouteMap);
//            exit();
//        }
        return self::createApiItems($files, $fixedApiRouteMap);
    }

    public static function generateApp()
    {
        $apiRouteFile = base_path('app/Api/routes.php');
        $apiRouteMap = [];
        if (file_exists($apiRouteFile)) {
            $mat = file_get_contents($apiRouteFile);
            preg_match_all('/[\'"]([a-zA-Z0-9_\\/]+)[\'"],\\s*[\'"]([A-Za-z0-9]+@[A-Za-z0-9]+)[\'"]\\)\\s*;/', $mat, $mat);
            if (!empty($mat[1])) {
                foreach ($mat[1] as $i => $v) {
                    $apiRouteMap[$mat[2][$i]] = $v;
                }
            }
        }
        $fixedApiRouteMap = [];
        foreach ($apiRouteMap as $ca => $u) {
            if (!starts_with($ca, 'Module')) {
                $ca = '\\App\\Api\\Controller\\' . $ca;
            }
            if (!starts_with($u, 'api')) {
                $u = modstart_api_url($u);
            }
            $fixedApiRouteMap[$ca] = $u;
        }
        $apiControllerDir = base_path('app/Api/Controller');
        $files = array_filter(FileUtil::listAllFiles($apiControllerDir), function ($f) {
            return $f['ext'] == 'php';
        });
        return self::createApiItems($files, $fixedApiRouteMap);
    }

    private static function createApiItems($files, $fixedApiRouteMap)
    {
        $classList = [];
        foreach ($files as $file) {
            $cls = substr($file['filename'], 0, -4);
            $mat = file_get_contents($file['pathname']);
            if (!preg_match('/namespace (.*?);/', $mat, $mat)) {
                continue;
            }
            $fullCls = '\\' . trim($mat[1]) . '\\' . $cls;
            if (class_exists($fullCls)) {
                $classList[] = $fullCls;
            }
        }

        $apiItems = [];
        foreach ($classList as $classItem) {
            $reflection = new \ReflectionClass($classItem);
            $controllerDoc = $reflection->getDocComment();
            $controllerDocBlock = new DocBlock($controllerDoc);
            $group = null;
            if ($controllerDocBlock->hasTag('Api')) {
                $group = $controllerDocBlock->getTagsByName('Api')[0]->getContent();
            }
            foreach ($reflection->getMethods() as $method) {
                $methodDoc = $method->getDocComment();
                if (empty($methodDoc)) {
                    continue;
                }
                $methodDocBlock = new DocBlock($methodDoc);
                if (!$methodDocBlock->hasTag('Api')) {
                    continue;
                }
                $item = [
                    'group' => $group,
                    'title' => $methodDocBlock->getTagsByName('Api')[0]->getContent(),
                    'desc' => null,
                    'url' => null,
                    'method' => 'post',
                    'dataType' => 'json',
                    'response' => null,
                    'responseData' => null,
                    'responseCode' => [
                        -1 => '默认错误',
                    ],
                    'headParam' => [],
                    'queryParam' => [],
                    'bodyParam' => [],
                ];
                if (isset($fixedApiRouteMap[$classItem . '@' . $method->getName()])) {
                    $item['url'] = $fixedApiRouteMap[$classItem . '@' . $method->getName()];
                }
                if ($methodDocBlock->hasTag('ApiDesc')) {
                    $item['desc'] = $methodDocBlock->getTagsByName('ApiDesc')[0]->getContent();
                }
                if ($methodDocBlock->hasTag('ApiMethod')) {
                    $item['method'] = strtolower($methodDocBlock->getTagsByName('ApiMethod')[0]->getContent());
                    BizException::throwsIf('ApiMethod 只允许 get, post', !in_array($item['method'], ['get', 'post']));
                }
                if ($methodDocBlock->hasTag('ApiDataType')) {
                    $item['dataType'] = strtolower($methodDocBlock->getTagsByName('ApiDataType')[0]->getContent());
                    BizException::throwsIf('ApiDataType 只允许 json, formData', !in_array($item['dataType'], ['json', 'formData']));
                }
                if ($methodDocBlock->hasTag('ApiResponse')) {
                    $item['response'] = json_decode($methodDocBlock->getTagsByName('ApiResponse')[0]->getContent(), true);
                }
                if ($methodDocBlock->hasTag('ApiResponseData')) {
                    $item['responseData'] = json_decode($methodDocBlock->getTagsByName('ApiResponseData')[0]->getContent(), true);
                }
                foreach ($methodDocBlock->getTagsByName('ApiHeadParam') as $tag) {
                    $item['headParam'][] = DocBlockUtil::parseField($tag);
                }
                foreach ($methodDocBlock->getTagsByName('ApiQueryParam') as $tag) {
                    $item['queryParam'][] = DocBlockUtil::parseField($tag);
                }
                foreach ($methodDocBlock->getTagsByName('ApiBodyParam') as $tag) {
                    $item['bodyParam'][] = DocBlockUtil::parseField($tag);
                }
                foreach ($methodDocBlock->getTagsByName('ApiResponseCode') as $tag) {
                    if (!preg_match('/(.+?)\s+(.*)/', $tag->getContent(), $mat)) {
                        continue;
                    }
                    $item['responseCode'][intval($mat[1])] = trim($mat[2]);
                }
                $apiItems[] = $item;
            }
        }
        return $apiItems;
    }

}
