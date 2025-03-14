<?php


namespace Module\ModuleDeveloper\Util;


use ModStart\Module\ModuleManager;

class ModuleParseUtil
{
    public static function getAdminMenu($module)
    {
        $file = ModuleManager::path($module, 'Core/ModuleServiceProvider.php');
        if (!file_exists($file)) {
            return [];
        }
        $content = file_get_contents($file);
        preg_match('/AdminMenu::register\(function\s*\(\)\s*{(.+?)\}\);/s', $content, $mat);
        if (empty($mat[1])) {
            return [];
        }
        $lines = explode("\n", $mat[1]);
        $menu = [];
        foreach ($lines as $line) {
            if (preg_match('/^(\\s+)\'title\' => \'(.*?)\'/', $line, $mat)) {
                $l = $mat[1] . $mat[2];
                $l = str_replace("\t", "    ", $l);
                $menu[] = $l;
            } else if (preg_match('/^(\\s+)\'title\' => L\\(\'(.*?)\'\\)/', $line, $mat)) {
                $l = $mat[1] . L($mat[2]);
                $l = str_replace("\t", "    ", $l);
                $menu[] = $l;
            }
        }
        if (empty($menu)) {
            return [];
        }
        if (!preg_match('/^(\\s+)([^\\s+]+)$/', $menu[0], $mat)) {
            return [];
        }
        $len = strlen($mat[1]);
        $menu = array_map(function ($item) use ($len) {
            return substr($item, $len);
        }, $menu);
        $menuList = [];
        foreach ($menu as $menuItem) {
            $char = '';
            $title = $menuItem;
            if (preg_match('/^(\\s+)(.*?)$/', $menuItem, $mat)) {
                $char = $mat[1];
                $title = $mat[2];
            }
            $menuList[] = [
                'title' => $title,
                // 'mat' => $mat,
                'char' => $char,
                'len' => strlen($char),
            ];
        }
        $chain = [];
        $adminMenuList = [];
        foreach ($menuList as $i => $item) {
            while (!empty($chain) && $chain[count($chain) - 1]['len'] >= $item['len']) {
                array_pop($chain);
            }
            $chain[] = $item;
            $menuList[$i]['chain'] = $chain;
            // $lastItem = $item;
            $adminMenuList[] = array_map(function ($item) {
                return $item['title'];
            }, $chain);
        }
        return $adminMenuList;
    }

    public static function getUrls($module)
    {
        $urls = [];
        foreach (['Api', 'Web'] as $item) {
            $urls[$item] = [];
            $file = ModuleManager::path($module, $item . '/routes.php');
            if (!file_exists($file)) {
                continue;
            }
            $content = file_get_contents($file);
            // $router->match(['post'], 'login', 'AuthController@login');
            preg_match_all('/\\$router->match\\(\\[(.+?)\\]\\s*,\s*\'(.+?)\'\\s*,\s*\'(.+?)\'\\)/', $content, $mat);
            if (empty($mat[0])) {
                continue;
            }
            foreach ($mat[0] as $i => $method) {
                $info = [
                    'method' => [],
                    'url' => $mat[2][$i],
                ];
                foreach (['get', 'post'] as $m) {
                    if (strpos($mat[1][$i], $m) !== false) {
                        $info['method'][] = $m;
                    }
                }
                $urls[$item][] = $info;
            }
        }
        return $urls;
    }
}
