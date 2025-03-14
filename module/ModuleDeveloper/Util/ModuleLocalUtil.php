<?php


namespace Module\ModuleDeveloper\Util;

use ModStart\Core\Util\FileUtil;

class ModuleLocalUtil
{
    public static function listAll($moduleDir)
    {
        $modules = [];
        if (!file_exists($moduleDir)) {
            return $modules;
        }
        $files = FileUtil::listFiles($moduleDir);
        foreach ($files as $v) {
            if (!$v['isDir']) {
                continue;
            }
            if (starts_with($v['filename'], '_delete_.')) {
                continue;
            }
            $modules[$v['filename']] = [
                'enable' => false,
                'isSystem' => false,
                'isInstalled' => false,
                'config' => [],
            ];
        }
        return $modules;
    }

    /**
     * 获取模块的基本信息
     * @param $name
     * @return array|null
     */
    public static function getModuleBasic($moduleDir, $name)
    {
        $path = rtrim($moduleDir, '/\\') . '/' . $name . '/config.json';
        if (file_exists($path)) {
            $config = json_decode(file_get_contents($path), true);
            return array_merge([
                'name' => 'None',
                'title' => 'None',
                'version' => '1.0.0',
                'require' => [
                    // 'Xxx:*'
                    // 'Xxx:>=*'
                    // 'Xxx:==*'
                    // 'Xxx:<=*'
                    // 'Xxx:>*'
                    // 'Xxx:<*'
                ],
                // 已知冲突模块
                'conflicts' => [
                    // 'Xxx:*'
                    // 'Xxx:>=*'
                    // 'Xxx:==*'
                    // 'Xxx:<=*'
                    // 'Xxx:>*'
                    // 'Xxx:<*'
                ],
                'modstartVersion' => '*',
                'author' => 'Author',
                'description' => 'Description',
                'config' => [],
                'providers' => [],
            ], $config);
        }
        return null;
    }
}
