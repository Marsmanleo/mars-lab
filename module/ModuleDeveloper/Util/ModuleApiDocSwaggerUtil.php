<?php


namespace Module\ModuleDeveloper\Util;


use ModStart\Core\Input\Response;
use ModStart\Module\ModuleManager;

class ModuleApiDocSwaggerUtil
{

    private static function buildParameter($in, $param)
    {
        $parameter = [];
        $parameter['name'] = $param['name'];
        $parameter['in'] = $in;
        $parameter['required'] = boolval($param['required']);
        $parameter['description'] = $param['desc'];
        $parameter['type'] = $param['type'];
        return $parameter;
    }

    private static function assignArrayByPath(&$arr, $path, $value, $separator = '.')
    {
        $keys = explode($separator, $path);

        foreach ($keys as $key) {
            $arr = &$arr[$key];
        }

        $arr = $value;
    }


    public static function generate($module)
    {
        $apiItems = ModuleApiDocUtil::generate($module);
        $moduleInfo = ModuleManager::getModuleBasic($module);
        $paths = [];
        foreach ($apiItems as $apiItem) {
            if (empty($apiItem['url'])) {
                continue;
            }
            $parameters = [];
            foreach ($apiItem['headParam'] as $param) {
                $parameters[] = self::buildParameter('header', $param);
            }
            foreach ($apiItem['queryParam'] as $param) {
                $parameters[] = self::buildParameter('path', $param);
            }
            switch ($apiItem['dataType']) {
                case 'json':
                    $example = [];
                    $properties = [];
                    $required = [];
                    foreach ($apiItem['bodyParam'] as $param) {
                        self::assignArrayByPath($example, $param['name'], $param['value']);
                        $properties[$param['name']] = [
                            'type' => $param['type'],
                            'description' => $param['desc'],
                        ];
                        if (!empty($param['required'])) {
                            $required[] = $param['name'];
                        }
                    }
                    $parameters[] = [
                        'in' => 'body',
                        'name' => 'body',
                        'description' => 'body',
                        'required' => true,
                        'schema' => [
                            'type' => 'object',
                            'properties' => $properties,
                            'required' => $required,
                            'example' => empty($example) ? new \stdClass() : $example
                        ]
                    ];
                    break;
                case 'formData':
                    foreach ($apiItem['bodyParam'] as $param) {
                        $parameters[] = self::buildParameter('formData', $param);
                    }
                    break;
            }
            $responses = [];
            $responses['200'] = [
                'description' => '成功',
                'schema' => [
                    'type' => 'object',
                    'example' => [
                        'code' => 0,
                        'msg' => '',
                        'data' => empty($apiItem['responseData']) ? new \stdClass() : $apiItem['responseData']
                    ],
                ]
            ];
            $paths[$apiItem['url']][$apiItem['method']] = [
                'summary' => $apiItem['title'],
                'description' => $apiItem['desc'],
                'tags' => [
                    $apiItem['group']
                ],
                'consumes' => [
                    $apiItem['dataType'] == 'json' ? 'application/json' : 'multipart/form-data',
                ],
                'produces' => [
                    'application/json'
                ],
                'parameters' => $parameters,
                'responses' => $responses,
            ];
        }
        $swaggerJson = [
            'swagger' => '2.0',
            'info' => [
                'title' => $moduleInfo['title'] . '（' . $moduleInfo['name'] . '）',
                'description' => $moduleInfo['description'],
                'version' => $moduleInfo['version'],
                'termsOfService' => 'https://modstart.com/m/' . $moduleInfo['name'],
                'contact' => [
                    'website' => 'https://modstart.com/m/' . $moduleInfo['name'],
                ]
            ],
            'basePath' => '',
            'paths' => $paths
        ];
        if (empty($paths)) {
            $swaggerJson = null;
        }
        return Response::generateSuccessData([
            'json' => $swaggerJson,
        ]);
    }

    public static function generateApp()
    {
        $modules = ModuleManager::listAllEnabledModules();
        $apiItems = [];
        foreach ($modules as $module => $_) {
            $moduleApiItems = ModuleApiDocUtil::generate($module);
            $apiItems = array_merge($apiItems, $moduleApiItems);
        }
        $apiItems = array_merge($apiItems, ModuleApiDocUtil::generateApp());
        $paths = [];
        foreach ($apiItems as $apiItem) {
            if (empty($apiItem['url'])) {
                continue;
            }
            $parameters = [];
            foreach ($apiItem['headParam'] as $param) {
                $parameters[] = self::buildParameter('header', $param);
            }
            foreach ($apiItem['queryParam'] as $param) {
                $parameters[] = self::buildParameter('path', $param);
            }
            switch ($apiItem['dataType']) {
                case 'json':
                    $example = [];
                    $properties = [];
                    $required = [];
                    foreach ($apiItem['bodyParam'] as $param) {
                        self::assignArrayByPath($example, $param['name'], $param['value']);
                        $properties[$param['name']] = [
                            'type' => $param['type'],
                            'description' => $param['desc'],
                        ];
                        if (!empty($param['required'])) {
                            $required[] = $param['name'];
                        }
                    }
                    $parameters[] = [
                        'in' => 'body',
                        'name' => 'body',
                        'description' => 'body',
                        'required' => true,
                        'schema' => [
                            'type' => 'object',
                            'properties' => $properties,
                            'required' => $required,
                            'example' => empty($example) ? new \stdClass() : $example
                        ]
                    ];
                    break;
                case 'formData':
                    foreach ($apiItem['bodyParam'] as $param) {
                        $parameters[] = self::buildParameter('formData', $param);
                    }
                    break;
            }
            $responses = [];
            $responses['200'] = [
                'description' => '成功',
                'schema' => [
                    'type' => 'object',
                    'example' => [
                        'code' => 0,
                        'msg' => '',
                        'data' => empty($apiItem['responseData']) ? new \stdClass() : $apiItem['responseData']
                    ],
                ]
            ];
            $paths[$apiItem['url']][$apiItem['method']] = [
                'summary' => $apiItem['title'],
                'description' => $apiItem['desc'],
                'tags' => [
                    $apiItem['group']
                ],
                'consumes' => [
                    $apiItem['dataType'] == 'json' ? 'application/json' : 'multipart/form-data',
                ],
                'produces' => [
                    'application/json'
                ],
                'parameters' => $parameters,
                'responses' => $responses,
            ];
        }
        $title = 'ModStart';
        $version = '1.0.0';
        if (class_exists('\App\Constant\AppConstant')) {
            if (defined('\App\Constant\AppConstant::APP_NAME')) {
                $title = \App\Constant\AppConstant::APP_NAME;
            }
            if (defined('\App\Constant\AppConstant::VERSION')) {
                $version = \App\Constant\AppConstant::VERSION;
            }
        }
        $swaggerJson = [
            'swagger' => '2.0',
            'info' => [
                'title' => $title,
                'description' => '',
                'version' => $version,
            ],
            'basePath' => '',
            'paths' => $paths
        ];
        if (empty($paths)) {
            $swaggerJson = null;
        }
        return Response::generateSuccessData([
            'json' => $swaggerJson,
            'title' => $title,
        ]);
    }
}
