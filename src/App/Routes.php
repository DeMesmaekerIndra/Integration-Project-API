<?php

declare (strict_types = 1);

use App\Middleware\ValidateRequestMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/opo', function (RouteCollectorproxy $group) {
    $group->get('', 'App\Controller\OpoController:getAll');
    $group->post('', 'App\Controller\OpoController:create')
        ->add(new ValidateRequestMiddleware(array("Code", "Naam", "Studiepunten", "IsActief", "Jaarduur", "Fase_FK")));
    $group->get('/{id}', 'App\Controller\OpoController:get');
    $group->put('/{id}', 'App\Controller\OpoController:update')
        ->add(new ValidateRequestMiddleware(array("Code", "Naam", "Studiepunten", "IsActief", "Jaarduur", "Fase_FK")));
});

/*
$app->group('/ola', function (RouteCollectorProxy $group) {
$group->get('', 'App\Controller\OpoController:getAll');
$group->post('', 'App\Controller\OpoController:create')
->add(new ValidateRequestMiddleware(array("Code", "Naam", "Studiepunten", "IsActief", "Jaarduur", "Coordinator_FK", "Fase_FK")));
$group->get('/{id}', 'App\Controller\OpoController:get');
$group->put('/{id}', 'App\Controller\OpoController:update')
->add(new ValidateRequestMiddleware(array("Code", "Naam", "Studiepunten", "IsActief", "Jaarduur", "Coordinator_FK", "Fase_FK")));
});

$app->group('/Personeel', function (RouteCollectorproxy $group) {
$group->get('/', 'App\Controller\OpoController:getAll');
$group->get('/{id}', 'App\Controller\OpoController:get');
$group->get('/allByOla/{olaId}', 'App\Controller\OpoController:get');
});*/
