<?php
/* @var \Illuminate\Routing\Router $router */

$router->match(['get', 'post'], 'cms_template_corp/config/basic', 'ConfigController@basic');
$router->match(['get', 'post'], 'cms_template_corp/config/demo_data', 'ConfigController@demoData');
