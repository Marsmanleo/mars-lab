<?php

/* @var \Illuminate\Routing\Router $router */

$router->match(['get', 'post'], 'i18n/lang', 'LangController@index');
$router->match(['get', 'post'], 'i18n/lang/add', 'LangController@add');
$router->match(['get', 'post'], 'i18n/lang/edit', 'LangController@edit');
$router->match(['post'], 'i18n/lang/delete', 'LangController@delete');
$router->match(['get'], 'i18n/lang/show', 'LangController@show');
$router->match(['post'], 'i18n/lang/sort', 'LangController@sort');

$router->match(['get', 'post'], 'i18n/lang_trans', 'LangTransController@index');
$router->match(['get', 'post'], 'i18n/lang_trans/add', 'LangTransController@add');
$router->match(['get', 'post'], 'i18n/lang_trans/edit', 'LangTransController@edit');
$router->match(['post'], 'i18n/lang_trans/delete', 'LangTransController@delete');
$router->match(['get'], 'i18n/lang_trans/show', 'LangTransController@show');
