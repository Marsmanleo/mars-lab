<?php

/* @var \Illuminate\Routing\Router $router */

$router->match(['get', 'post'], 'module_developer', 'ModuleDeveloperController@index');
$router->match(['get', 'post'], 'module_developer/all', 'ModuleDeveloperController@all');
$router->match(['get', 'post'], 'module_developer/publish', 'ModuleDeveloperController@publish');
$router->match(['get', 'post'], 'module_developer/publish_info', 'ModuleDeveloperController@publishInfo');
$router->match(['get', 'post'], 'module_developer/api_doc', 'ModuleDeveloperController@apiDoc');
$router->match(['get', 'post'], 'module_developer/util_doc', 'ModuleDeveloperController@utilDoc');
$router->match(['get', 'post'], 'module_developer/download_zip', 'ModuleDeveloperController@downloadZip');

$router->match(['get', 'post'], 'module_developer/tools/add_module', 'ModuleDeveloperToolsController@addModule');
$router->match(['get', 'post'], 'module_developer/tools/trash_module', 'ModuleDeveloperToolsController@trashModule');
$router->match(['post'], 'module_developer/tools/trash_module/delete', 'ModuleDeveloperToolsController@trashModuleDelete');
$router->match(['get', 'post'], 'module_developer/tools/all_api', 'ModuleDeveloperToolsController@allApi');
$router->match(['get'], 'module_developer/tools/all_db_structure', 'ModuleDeveloperToolsController@allDbStructure');
$router->match(['get', 'post'], 'module_developer/tools/package_file', 'ModuleDeveloperToolsController@packageFile');
