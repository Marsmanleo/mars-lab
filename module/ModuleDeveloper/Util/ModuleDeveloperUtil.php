<?php


namespace Module\ModuleDeveloper\Util;


use Chumper\Zipper\Zipper;
use Illuminate\Support\Str;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\CurlUtil;
use ModStart\Core\Util\FileUtil;
use ModStart\Core\Util\FormatUtil;
use ModStart\Core\Util\SerializeUtil;
use Module\Vendor\Markdown\MarkdownUtil;

class ModuleDeveloperUtil
{
    const REMOTE_BASE = 'https://modstart.com';

    private static function baseRequest($api, $data, $token)
    {
        return CurlUtil::postJSONBody(self::REMOTE_BASE . $api, $data, [
            'header' => [
                'api-token' => $token,
                'X-Requested-With' => 'XMLHttpRequest',
            ]
        ]);
    }

    private static function getMarkdownHtmlContent($file)
    {
        $content = '';
        if (file_exists($file)) {
            $content = trim(file_get_contents($file));
            $content = MarkdownUtil::convertToHtml($content);
        }
        return $content;
    }

    private static function getMarkdownArrayContent($file)
    {
        $array = [];
        if (file_exists($file)) {
            $content = trim(file_get_contents($file));
            $items = explode("\n", $content);
            $items = array_filter(array_map(function ($item) {
                return trim($item);
            }, $items));
            $items = array_map(function ($item) {
                if (preg_match('/^!\[.*?\]\((.*?)\)$/', $item, $mat)) {
                    return $mat[1];
                }
                return $item;
            }, $items);
            $array = array_values(array_filter($items, function ($item) {
                return $item && FormatUtil::isUrl($item);
            }));
        }
        return $array;
    }

    private static function getModuleDocs($moduleDir, $module)
    {
        $moduleDocs = [];
        $docs = FileUtil::listFiles("$moduleDir/Docs/doc/", '*.md');
        foreach ($docs as $doc) {
            $content = file_get_contents($doc['pathname']);
            $content = explode('---', $content);
            BizException::throwsIf("模块配置文件 module/$module/Docs/doc/$doc[filename] 格式不正确，请参照 Demo/Docs/doc/ 文件格式", count($content) < 2);
            $title = trim($content[0]);
            $title = trim(preg_replace('/^#+/', '', $title));
            array_shift($content);
            $content = MarkdownUtil::convertToHtml(trim(join('---', $content)));
            $moduleDocs[] = [
                'name' => substr($doc['filename'], 0, -3),
                'title' => $title,
                'content' => $content,
            ];
        }
        return $moduleDocs;
    }

    private static function replaceBuildModuleContentParam($module, $moduleContent)
    {
        $replaces = [];
        if (Str::contains($moduleContent, '{ADMIN_MENUS}')) {
            $adminMenu = ModuleParseUtil::getAdminMenu($module);
            $html = [];
            if (!empty($adminMenu)) {
                $html[] = "<h2>后台菜单</h2>";
                $html[] = "<ul>";
                foreach ($adminMenu as $item) {
                    $item = array_map(function ($item) {
                        return '<span class="ub-tag">' . $item . '</span>';
                    }, $item);
                    $html[] = '<li>' . implode('<span class="ub-text-muted"> → </span>', $item) . '</li>';
                }
                $html[] = "</ul>";
            }
            $replaces['<p>{ADMIN_MENUS}</p>'] = join("\n", $html);
        }

        if (Str::contains($moduleContent, '{WEB_ROUTES}')) {
            $webUrls = ModuleParseUtil::getUrls($module);
            $html = [];
            if (!empty($webUrls['Web'])) {
                $html[] = "<h2>前台地址</h2>";
                $html[] = "<ul>";
                foreach ($webUrls['Web'] as $item) {
                    $html[] = '<li><span class="primary tw-font-mono tw-mr-2 ub-tag">' . join('|', $item['method']) . '</span>'
                        . '<span class="ub-tag">' . $item['url'] . '</span></li>';
                }
                $html[] = "</ul>";
            }
            $replaces['<p>{WEB_ROUTES}</p>'] = join("\n", $html);
        }

        if (!empty($replaces)) {
            $moduleContent = str_replace(array_keys($replaces), array_values($replaces), $moduleContent);
        }
        return $moduleContent;
    }

    public static function getModuleInfo($module, $version, $moduleDir = null)
    {
        if (null === $moduleDir) {
            $moduleDir = base_path('module/' . $module);
        } else {
            $moduleDir = rtrim($moduleDir, '/\\') . '/' . $module;
        }

        BizException::throwsIf('模块目录 module/' . $module . ' 不正常，请手动删除', !file_exists($moduleDir) || !is_dir($moduleDir));
        BizException::throwsIf('模块目录 module/' . $module . ' 不正常，请手动删除', !file_exists($moduleDir . '/config.json'));

        $moduleContent = self::getMarkdownHtmlContent("$moduleDir/Docs/module/content.md");
        $moduleContent = self::replaceBuildModuleContentParam($module, $moduleContent);
        $moduleDemo = self::getMarkdownHtmlContent("$moduleDir/Docs/module/demo.md");
        $modulePreview = self::getMarkdownArrayContent("$moduleDir/Docs/module/preview.md");
        $moduleMobilePreview = self::getMarkdownArrayContent("$moduleDir/Docs/module/mobilePreview.md");
        $moduleDocs = self::getModuleDocs($moduleDir, $module);

        return Response::generateSuccessData([
            'moduleContent' => $moduleContent,
            'moduleDemo' => $moduleDemo,
            'modulePreview' => SerializeUtil::jsonEncode($modulePreview),
            'moduleMobilePreview' => SerializeUtil::jsonEncode($moduleMobilePreview),
            'moduleDocs' => SerializeUtil::jsonEncode($moduleDocs),
        ]);
    }

    public static function packModule($module, $version)
    {
        $moduleDir = base_path('module/' . $module);
        BizException::throwsIf('模块目录 module/' . $module . ' 不正常，请手动删除', !file_exists($moduleDir) || !is_dir($moduleDir));
        BizException::throwsIf('模块目录 module/' . $module . ' 不正常，请手动删除', !file_exists($moduleDir . '/config.json'));
        $config = @json_decode(file_get_contents($moduleDir . '/config.json'), true);
        BizException::throwsIfEmpty("模块配置文件 module/$module/config.json 解析错误", $config);
        BizException::throwsIf("模块配置文件 module/$module/config.json 标识解析错误（模块名称异常）", empty($config['name']) || $config['name'] != $module);
        BizException::throwsIf("模块配置文件 module/$module/config.json 标识解析错误", empty($config['name']) || $config['name'] != $module);
        BizException::throwsIf("模块配置文件 module/$module/config.json 版本解析错误（获取版本号失败）", empty($config['version']));
        BizException::throwsIf("模块配置文件 module/$module/config.json 版本解析错误（版本号不匹配，配置文件 $config[version]，期待 $version ）", $config['version'] != $version);

        // 获取Release信息
        $releaseFeature = null;
        $releaseContent = null;

        $releaseFile = $moduleDir . '/Docs/release.md';
        if (file_exists($releaseFile)) {
            $releaseParts = explode('---', file_get_contents($releaseFile));
            $releaseParts = array_filter(array_build($releaseParts, function ($k, $part) {
                $version = null;
                $feature = null;
                $content = [];
                $lines = explode("\n", trim($part));
                foreach ($lines as $line) {
                    if (preg_match('/^##\s*(\\d+\\.\\d+\\.\\d+)\s*(.*)$/', $line, $mat)) {
                        $version = $mat[1];
                        $feature = $mat[2];
                    } else {
                        $content[] = $line;
                    }
                }
                if (!empty($version)) {
                    return [
                        $version,
                        [
                            'feature' => $feature,
                            'content' => join("\n", $content),
                        ]
                    ];
                }
                return [null, null];
            }));
            if (isset($releaseParts[$version])) {
                $releaseFeature = $releaseParts[$version]['feature'];
                $releaseContent = MarkdownUtil::convertToHtml($releaseParts[$version]['content']);
            }
        }
        if (empty($releaseFeature)) {
            $releaseFile = $moduleDir . "/Docs/release/$version.md";
            if (file_exists($releaseFile)) {
                $releaseNotesContent = file_get_contents($moduleDir . "/Docs/release/$version.md");
                $releaseNotes = explode('---', $releaseNotesContent);
                BizException::throwsIf("模块配置文件 module/$module/Docs/release/$version.md 格式不正确，请参照 Demo/Docs/release/ 文件格式", count($releaseNotes) < 2);
                $releaseFeature = trim($releaseNotes[0]);
                array_shift($releaseNotes);
                $releaseContent = trim(join('---', $releaseNotes));
                BizException::throwsIfEmpty("模块配置文件 module/$module/Docs/release/$version.md 格式不正确，请参照 Demo/Docs/release/ 文件格式", $releaseFeature);
                BizException::throwsIfEmpty("模块配置文件 module/$module/Docs/release/$version.md 格式不正确，请参照 Demo/Docs/release/ 文件格式", $releaseContent);
                $releaseContent = MarkdownUtil::convertToHtml($releaseContent);
            }
        }
        if (empty($releaseFeature)) {
            BizException::throws("模块配置更新日志文件错误，请参照 Demo/Docs/release.md 文件格式");
        }

        $apiRet = ModuleApiDocSwaggerUtil::generate($module);
        BizException::throwsIfResponseError($apiRet);
        $apiContent = $apiRet['data']['json'];

        $utilRet = ModuleUtilDocUtil::generate($module);
        BizException::throwsIfResponseError($utilRet);
        $utilContent = $utilRet['data']['json'];

        $moduleContent = self::getMarkdownHtmlContent("$moduleDir/Docs/module/content.md");
        $moduleContent = self::replaceBuildModuleContentParam($module, $moduleContent);
        $moduleDemo = self::getMarkdownHtmlContent("$moduleDir/Docs/module/demo.md");
        $modulePreview = self::getMarkdownArrayContent("$moduleDir/Docs/module/preview.md");
        $moduleMobilePreview = self::getMarkdownArrayContent("$moduleDir/Docs/module/mobilePreview.md");
        $moduleDocs = self::getModuleDocs($moduleDir, $module);

        $zipTemp = FileUtil::generateLocalTempPath('zip');
        $files = FileUtil::listAllFiles($moduleDir, function ($file) {
            $filenameIgnores = [
                'Makefile'
            ];
            foreach ($filenameIgnores as $item) {
                if ($file['filename'] == $item) {
                    return false;
                }
            }
            $dirIgnores = [
                'Docs/doc',
            ];
            foreach ($dirIgnores as $item) {
                if (Str::endsWith($file['path'], $item)) {
                    return false;
                }
            }
            $extIgnores = [
                'ms-backup'
            ];
            foreach ($extIgnores as $item) {
                if ($file['ext'] == $item) {
                    return false;
                }
            }
            if ($file['isDir'] && in_array($file['filename'], ['node_modules', '.git', '.idea', '.hbuilderx', 'license.json'])) {
                return false;
            }
            if ($file['isDir'] && file_exists($file['pathname'] . '/.ms-ignore')) {
                return false;
            }
            if ($file['isDir'] && file_exists($file['pathname'] . '.ms-ignore')) {
                return false;
            }
            return true;
        });
        $zipper = new Zipper();
        $zipper->make($zipTemp);
        $fileCount = 0;
        foreach ($files as $file) {
            if ($file['isDir']) {
                continue;
            }
            $file['filename'] = str_replace('\\', '/', $file['filename']);
            if ('php' == $file['ext'] && !ends_with($file['filename'], '.blade.php')) {
                $code = file_get_contents($file['pathname']);
                $zipper->addString($file['filename'], $code);
            } else {
                $zipper->add($file['pathname'], $file['filename']);
            }
            $fileCount++;
        }
        $zipper->close();

        $fileSize = filesize($zipTemp);
        if ($fileSize > 10 * 1024 * 1024) {
            FileUtil::safeCleanLocalTemp($zipTemp);
            BizException::throws('模块打包文件太大（超过10M），请手动上传');
        }

        return Response::generateSuccessData([
            'package' => $zipTemp,
            'fileSize' => $fileSize,
            'fileCount' => $fileCount,
            'releaseFeature' => $releaseFeature,
            'releaseContent' => $releaseContent,
            'apiContent' => SerializeUtil::jsonEncode($apiContent),
            'utilContent' => SerializeUtil::jsonEncode($utilContent),
            'moduleContent' => $moduleContent,
            'moduleDemo' => $moduleDemo,
            'modulePreview' => SerializeUtil::jsonEncode($modulePreview),
            'moduleMobilePreview' => SerializeUtil::jsonEncode($moduleMobilePreview),
            'moduleDocs' => SerializeUtil::jsonEncode($moduleDocs),
        ]);
    }

    private static function buildModuleData($moduleConfig)
    {
        $moduleData = [];
        if (isset($moduleConfig['tags'])) {
            $moduleData['tags'] = $moduleConfig['tags'];
        }
        $apiRet = ModuleApiDocSwaggerUtil::generate($moduleConfig['name']);
        BizException::throwsIfResponseError($apiRet);
        $moduleData['apiContent'] = $apiRet['data']['json'];

        $utilRet = ModuleUtilDocUtil::generate($moduleConfig['name']);
        BizException::throwsIfResponseError($utilRet);
        $moduleData['utilContent'] = $utilRet['data']['json'];

        // print_r($moduleData);exit();
        return $moduleData;
    }

    public static function updateModuleInfo($token, $module, $version,
                                            $moduleContent, $moduleDemo, $moduleDocs,
                                            $modulePreview, $moduleMobilePreview,
                                            $moduleConfig)
    {

        $param = [
            'module' => $module,
            'version' => $version,
            'moduleContent' => $moduleContent,
            'moduleDemo' => $moduleDemo,
            'moduleDocs' => $moduleDocs,
            'modulePreview' => $modulePreview,
            'moduleMobilePreview' => $moduleMobilePreview,
            'moduleConfigTypes' => SerializeUtil::jsonEncode(isset($moduleConfig['types']) ? $moduleConfig['types'] : []),
            'moduleData' => SerializeUtil::jsonEncode(self::buildModuleData($moduleConfig)),
        ];
        // print_r($param);exit();
        $ret = self::baseRequest('/api/store/module_publish_info', $param, $token);
        BizException::throwsIfResponseError($ret);
        return Response::generateSuccessData([
            'msgs' => $ret['data']['msgs'],
        ]);
    }

    public static function uploadHotfix($token, $title, $content, $packageContent, $param = [])
    {
        $data = [
            'title' => $title,
            'content' => SerializeUtil::jsonEncode($content),
            'package' => base64_encode($packageContent),
        ];
        $ret = self::baseRequest('/api/store/module_publish_hotfix', $data, $token);
        BizException::throwsIfResponseError($ret);
        return Response::generate(0, null, [
            'text' => $ret['data']['text']
        ]);
    }

    public static function uploadModule($token, $module, $version, $package, $releaseFeature, $releaseContent,
                                        $moduleContent, $moduleDemo, $moduleDocs,
                                        $modulePreview, $moduleMobilePreview, $apiContent, $utilContent, $moduleConfig)
    {
        BizException::throwsIf('模块打包文件文件不存在', !file_exists($package));
        $param = [
            'module' => $module,
            'version' => $version,
            'feature' => $releaseFeature,
            'content' => $releaseContent,
            'package' => base64_encode(file_get_contents($package)),
            'moduleContent' => $moduleContent,
            'moduleDemo' => $moduleDemo,
            'moduleDocs' => $moduleDocs,
            'modulePreview' => $modulePreview,
            'moduleMobilePreview' => $moduleMobilePreview,
            'moduleConfigTypes' => SerializeUtil::jsonEncode(isset($moduleConfig['types']) ? $moduleConfig['types'] : []),
            'moduleData' => SerializeUtil::jsonEncode(self::buildModuleData($moduleConfig)),
            'apiContent' => $apiContent,
            'utilContent' => $utilContent,
        ];
        $ret = self::baseRequest('/api/store/module_publish', $param, $token);
        FileUtil::safeCleanLocalTemp($package);
        BizException::throwsIfResponseError($ret);
        FileUtil::safeCleanLocalTemp($package);
        return Response::generateSuccessData([
            'msgs' => $ret['data']['msgs'],
        ]);
    }

    public static function downloadPackage($token, $module, $version)
    {
        $ret = self::baseRequest('/api/store/module_package', [
            'module' => $module,
            'version' => $version,
        ], $token);
        BizException::throwsIfResponseError($ret);
        $package = $ret['data']['package'];
        $packageMd5 = $ret['data']['packageMd5'];
        $licenseKey = $ret['data']['licenseKey'];
        $data = CurlUtil::getRaw($package);
        BizException::throwsIfEmpty('安装包获取失败', $data);
        $zipTemp = FileUtil::generateLocalTempPath('zip');
        file_put_contents($zipTemp, $data);
        BizException::throwsIf('文件MD5校验失败', md5_file($zipTemp) != $packageMd5);
        return Response::generateSuccessData([
            'package' => $zipTemp,
            'licenseKey' => $licenseKey,
            'packageSize' => filesize($zipTemp),
        ]);
    }
}
