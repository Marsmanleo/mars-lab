<?php


namespace Module\ModuleDeveloper\Util;


use ModStart\Core\Exception\BizException;
use ModStart\Core\Util\FileUtil;

class ModuleTrashUtil
{
    const TRASH_MODULE_REGEX = '/^_delete_\\.(\\d{8,8})_(\\d{6,6})\\.([A-Za-z0-9]+)$/';

    public static function listAll()
    {
        $files = FileUtil::listFiles(base_path('module'));
        $modules = [];
        foreach ($files as $v) {
            if (!$v['isDir']) {
                continue;
            }
            if (!starts_with($v['filename'], '_delete_.')) {
                continue;
            }
            if (!preg_match(self::TRASH_MODULE_REGEX, $v['filename'], $mat)) {
                continue;
            }
            $name = $mat[3];
            $module = [
                'name' => $name,
                'filename' => $v['filename'],
                'title' => '',
                'version' => '',
                '_info' => null,
            ];
            $configFile = rtrim($v['pathname'], '/\\') . '/config.json';
            if (file_exists($configFile)) {
                $configJson = @json_decode(file_get_contents($configFile), true);
                $module['title'] = isset($configJson['title']) ? $configJson['title'] : '未知';
                $module['version'] = isset($configJson['version']) ? $configJson['version'] : '未知';
                $module['_info'] = $configJson;
            }
            $modules[] = $module;
        }
        return $modules;
    }

    public static function clean($filename)
    {
        BizException::throwsIfEmpty('文件名为空', $filename);
        BizException::throwsIf('文件名格式错误', !preg_match(self::TRASH_MODULE_REGEX, $filename));
        FileUtil::rm(base_path('module/' . $filename), true);
    }
}
