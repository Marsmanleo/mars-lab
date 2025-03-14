<?php

/* @var \Illuminate\Routing\Router $router */

$router->match(['get', 'post'], 'custom_field_demo/config', 'ConfigController@index');
