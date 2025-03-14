<?php

/* @var \Illuminate\Routing\Router $router */

$router->match(['get'], '{locale}/i18n_demo', 'I18nDemoController@index')->where(['lang' => '[a-z]{2}']);
$router->match(['get'], '{locale}/i18n_demo/{id}', 'I18nDemoController@show')->where(['lang' => '[a-z]{2}']);
$router->match(['get'], '{locale}/i18n_demo_category/{id}', 'I18nDemoCategoryController@index')->where(['lang' => '[a-z]{2}']);
