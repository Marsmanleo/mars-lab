<?php


namespace Module\ModuleDeveloper\Util;


use ModStart\Core\Input\Response;
use ModStart\Core\Util\FileUtil;
use ModStart\Module\ModuleManager;
use Mpociot\Reflection\DocBlock;

class ModuleUtilDocUtil
{
    /**
     * @param $module string
     * @param $name type 说明
     *
     * @return array
     * @throws \ReflectionException
     *
     * @doc 支持的注解
     *
     * 类
     * @Util 分组
     *
     * 方法
     * @Util 名称
     */
    public static function generate($module)
    {
        $path = ModuleManager::path($module);
        return self::generateByFolder($path);
    }

    public static function functionsToMarkdown($methods, $level = 3, $lang = 'php')
    {
        $titlePrefix = str_repeat('#', $level);
        $markdown = [];
        foreach ($methods as $method) {
            // print_r($method);
            $name = $method['name'];
            if (!empty($method['nameTitle'])) {
                $name = $method['nameTitle'];
            }
            $name = str_replace('_', '\\_', $name);
            $markdown[] = "$titlePrefix $name $method[title]\n";
            if (!empty($method['desc'])) {
                $markdown[] = "\n" . $method['desc'] . "\n";
            }
            $params = [];
            foreach ($method['params'] as $param) {
                $params[] = "$param[name]";
            }
            $params = join(', ', $params);
            $markdown[] = "`$method[name]( $params )`\n";
            foreach ($method['params'] as $param) {
                $markdown[] = "- 参数：`$param[name]` `$param[type]` $param[desc]";
            }
            $markdown[] = "- 返回：`" . $method['return']['type'] . "` " . $method['return']['desc'];
            if (!empty($method['example'])) {
                $markdown[] = "";
                $markdown[] = "代码示例";
                $markdown[] = "```$lang";
                $markdown[] = $method['example'];
                $markdown[] = "```";
            }
            $markdown[] = "";
        }
        return join("\n", $markdown);
    }

    public static function classMethodsToMarkdown($json)
    {
        $markdown = [];
        foreach ($json as $group) {
            $markdown[] = "## $group[title] $group[class]\n";
            $markdown[] = "<!-- doc:开发教程:{title}PHP库-$group[title]-$group[class] -->\n";
            foreach ($group['methods'] as $method) {
                // print_r($method);
                $markdown[] = "### $method[name] $method[title]\n";
                if (!empty($method['desc'])) {
                    $markdown[] = "\n" . $method['desc'] . "\n";
                }
                $params = [];
                foreach ($method['params'] as $param) {
                    $params[] = "$param[name]";
                }
                $params = join(', ', $params);
                $markdown[] = "`$group[class]::$method[name]( $params )`";
                foreach ($method['params'] as $param) {
                    $markdown[] = "- 参数：`$param[name]` `$param[type]` $param[desc]";
                }
                $markdown[] = "- 返回：`" . $method['return']['type'] . "` " . $method['return']['desc'];
                if (!empty($method['example'])) {
                    $markdown[] = "";
                    $markdown[] = "代码示例";
                    $markdown[] = "```php";
                    $markdown[] = $method['example'];
                    $markdown[] = "```";
                }
                $markdown[] = "";
            }
        }
        return join("\n", $markdown);
    }

    public static function generateByFolder($path)
    {
        $files = FileUtil::listAllFiles($path, function ($f) {
            if (in_array($f['filename'], ['node_modules', 'SDK'])) {
                return false;
            }
            if ($f['isFile']) {
                if (!in_array($f['ext'], ['php'])) {
                    return false;
                }
                if (ends_with($f['filename'], '.blade.php')) {
                    return false;
                }
            }
            return true;
        });
        $files = array_filter($files, function ($f) {
            return $f['isFile'];
        });
        $classList = [];
        foreach ($files as $file) {
            $content = file_get_contents($file['pathname']);
            $namespace = '\\';
            if (preg_match('/namespace (.*?);/', $content, $mat)) {
                $namespace = '\\' . trim($mat[1]) . '\\';
            }
            $cls = substr(basename($file['filename']), 0, -4);
            $fullCls = $namespace . $cls;
            if (class_exists($fullCls)) {
                $classList[] = $fullCls;
            }
        }
        return self::generateByClassList($classList);
    }

    public static function generateUserFunctions()
    {
        $items = [];
        $methods = get_defined_functions();
        foreach ($methods['user'] as $method) {
            $method = new \ReflectionFunction($method);
            $methodDoc = $method->getDocComment();
            if (empty($methodDoc)) {
                continue;
            }
            $methodDocBlock = new DocBlock($methodDoc);
            if (!$methodDocBlock->hasTag('Util')) {
                continue;
            }
            $m = [];
            $m['name'] = $method->getName();
            $m['title'] = $methodDocBlock->getTagsByName('Util')[0]->getContent();
            $descs = $methodDocBlock->getTagsByName('desc');
            $m['desc'] = empty($descs) ? '' : $descs[0]->getContent();
            if (empty($m['title'])) {
                $m['title'] = $methodDocBlock->getShortDescription();
            }
            $returns = $methodDocBlock->getTagsByName('return');
            $m['return'] = DocBlockUtil::parseReturn(empty($returns) ? null : $returns[0]);
            $examples = $methodDocBlock->getTagsByName('example');
            $m['example'] = DocBlockUtil::parseTagContent(empty($examples) ? null : $examples[0]);
            $params = [];
            foreach ($methodDocBlock->getTagsByName('param') as $tag) {
                $params[] = DocBlockUtil::parseField($tag);
            }
            $m['params'] = $params;
            $items[] = $m;
        }
        return $items;
    }

    public static function generateByClassList($classList)
    {
        $placeholderMap = [];
        $items = [];
        foreach ($classList as $classItem) {
            $reflection = new \ReflectionClass($classItem);
            $controllerDoc = $reflection->getDocComment();
            $controllerDocBlock = new DocBlock($controllerDoc);
            $groupTitle = null;
            if ($controllerDocBlock->hasTag('Util')) {
                $groupTitle = $controllerDocBlock->getTagsByName('Util')[0]->getContent();
            }
            if (empty($groupTitle)) {
                continue;
            }
            $placeholders = $controllerDocBlock->getTagsByName('Placeholder');
            foreach ($placeholders as $ph) {
                $phContent = $ph->getContent();
                if (empty($phContent)) {
                    continue;
                }
                if (!preg_match('/^@([a-zA-Z0-9]+)\\s(.*?)$/s', trim($phContent), $mat)) {
                    continue;
                }
                $placeholderMap[trim($mat[1])] = trim($mat[2]);
            }
            $pcs = explode('\\', $classItem);
            $cls = $pcs[count($pcs) - 1];
            $item = [
                'class' => $cls,
                'name' => $classItem,
                'title' => $groupTitle,
                'methods' => [],
            ];
            // 快捷操作类忽略之前的 \ namespace
            if (preg_match('/^\\\\M[A-Z]/', $item['name'])) {
                $item['name'] = substr($item['name'], 1);
            }
            foreach ($reflection->getMethods() as $method) {
                $methodDoc = $method->getDocComment();
                if (empty($methodDoc)) {
                    continue;
                }
                $methodDocBlock = new DocBlock($methodDoc);
                if (!$methodDocBlock->hasTag('Util')) {
                    continue;
                }
                $m = [];
                $m['name'] = $method->getName();
                $m['title'] = $methodDocBlock->getTagsByName('Util')[0]->getContent();
                $descs = $methodDocBlock->getTagsByName('desc');
                $m['desc'] = empty($descs) ? '' : $descs[0]->getContent();
                if (empty($m['title'])) {
                    $m['title'] = $methodDocBlock->getShortDescription();
                }
                $returns = $methodDocBlock->getTagsByName('return');
                $m['return'] = DocBlockUtil::parseReturn(empty($returns) ? null : $returns[0]);
                $returnExamples = $methodDocBlock->getTagsByName('returnExample');
                $m['returnExample'] = DocBlockUtil::parseTagContent(empty($returnExamples) ? null : $returnExamples[0]);
                $examples = $methodDocBlock->getTagsByName('example');
                $m['example'] = DocBlockUtil::parseTagContent(empty($examples) ? null : $examples[0]);
                $params = [];
                foreach ($methodDocBlock->getTagsByName('param') as $tag) {
                    $params[] = DocBlockUtil::parseField($tag);
                }
                $m['params'] = $params;
                $paramExamples = $methodDocBlock->getTagsByName('paramExample');
                $m['paramExample'] = DocBlockUtil::parseTagContent(empty($paramExamples) ? null : $paramExamples[0]);
                $item['methods'][] = $m;
            }
            $items[] = $item;
        }
        $param = [
            'placeholderMap' => $placeholderMap,
        ];
        foreach ($items as $k => $v) {
            foreach ($v['methods'] as $kk => $method) {
                $items[$k]['methods'][$kk]['example'] = self::replaceContentPlaceholder($method['example'], $param);
                $items[$k]['methods'][$kk]['returnExample'] = self::replaceContentPlaceholder($method['returnExample'], $param);
                $items[$k]['methods'][$kk]['paramExample'] = self::replaceContentPlaceholder($method['paramExample'], $param);
            }
        }
        return Response::generateSuccessData([
            'json' => $items,
        ]);
    }

    private static function replaceContentPlaceholder($content, $param = [])
    {
        if (!empty($param['placeholderMap'])) {
            $content = preg_replace_callback('/\\[Placeholder\.([a-zA-Z0-9]+)(\\|([a-z0-9:,]+))?\\]/',
                function ($mat) use (&$param) {
                    $content = $mat[0];
                    if (isset($param['placeholderMap'][$mat[1]])) {
                        $content = $param['placeholderMap'][$mat[1]];
                    }
                    if (!empty($mat[3])) {
                        $args = explode(',', $mat[3]);
                        foreach ($args as $v) {
                            if (strpos($v, ':') !== false) {
                                list($kk, $vv) = explode(':', $v);
                            } else {
                                $kk = $v;
                                $vv = null;
                            }
                            switch ($kk) {
                                case 'indent':
                                    $content = join("\n", array_map(function ($line) use ($vv) {
                                        return str_repeat(' ', intval($vv)) . $line;
                                    }, explode("\n", $content)));
                                    break;
                                case 'trim':
                                    $content = trim($content);
                                    break;
                            }
                        }
                    }
                    return $content;
                }, $content);
            $content = str_replace(array_keys($param['placeholderMap']), array_values($param['placeholderMap']), $content);
        }
        return $content;
    }
}
