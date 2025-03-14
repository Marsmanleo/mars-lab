<?php

/* @var \Illuminate\Routing\Router $router */

$router->match(['get', 'post'], 'i18n_site/config/setting', 'ConfigController@setting');



