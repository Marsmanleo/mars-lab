<?php

/* @var \Illuminate\Routing\Router $router */

$router->match(['get', 'post'], 'nav_i18n', 'NavController@index');
$router->match(['get', 'post'], 'nav_i18n/add', 'NavController@add');
$router->match(['get', 'post'], 'nav_i18n/edit', 'NavController@edit');
$router->match(['post'], 'nav_i18n/delete', 'NavController@delete');
$router->match(['get'], 'nav_i18n/show', 'NavController@show');
$router->match(['post'], 'nav_i18n/sort', 'NavController@sort');
