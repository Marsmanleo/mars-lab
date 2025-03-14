<?php

/* @var \Illuminate\Routing\Router $router */

$router->match(['get', 'post'], 'cms_theme_mars/config', 'ConfigController@index');
