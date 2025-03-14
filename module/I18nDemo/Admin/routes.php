<?php

/* @var \Illuminate\Routing\Router $router */
$router->match(['get', 'post'], 'i18n/config/index', 'ConfigController@index');

$router->match(['get', 'post'], 'i18n/demo_category', 'I18nDemoCategoryController@index');
$router->match(['get', 'post'], 'i18n/demo_category/add', 'I18nDemoCategoryController@add');
$router->match(['get', 'post'], 'i18n/demo_category/edit', 'I18nDemoCategoryController@edit');
$router->match(['post'], 'i18n/demo_category/delete', 'I18nDemoCategoryController@delete');
$router->match(['get'], 'i18n/demo_category/show', 'I18nDemoCategoryController@show');

$router->match(['get', 'post'], 'i18n/demo', 'I18nDemoController@index');
$router->match(['get', 'post'], 'i18n/demo/add', 'I18nDemoController@add');
$router->match(['get', 'post'], 'i18n/demo/edit', 'I18nDemoController@edit');
$router->match(['post'], 'i18n/demo/delete', 'I18nDemoController@delete');
$router->match(['get'], 'i18n/demo/show', 'I18nDemoController@show');
