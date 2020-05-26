<?php

declare (strict_types = 1);

use Slim\Routing\RouteCollectorProxy;

$app->group('/opo', function (RouteCollectorproxy $group) {
    $group->get('/', 'App\Controller\OpoController:getAll');
    $group->get('/{id}', 'App\Controller\OpoController:get');
});

$app->group('/ola', function (RouteCollectorProxy $group) {
    $group->get('/', 'App\Controller\OpoController:getAll');
    $group->get('/{id}', 'App\Controller\OpoController:get');
    $group->get('/allByOpo/{OpoId}', 'App\Controller\OpoController:get');
});

$app->group('/Personeel', function (RouteCollectorproxy $group) {
    $group->get('/', 'App\Controller\OpoController:getAll');
    $group->get('/{id}', 'App\Controller\OpoController:get');
    $group->get('/allByOla/{olaId}', 'App\Controller\OpoController:get');
});
