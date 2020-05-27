<?php

declare (strict_types = 1);

use Slim\Routing\RouteCollectorProxy;

$app->group('/opo', function (RouteCollectorproxy $group) {
    $group->get('', 'App\Controller\OpoController:getAll');
    $group->post('', 'App\Controller\OpoController:create')
        ->add(new App\Middleware\ValidateRequestMiddleware(array("Code", "Naam", "Studiepunten", "IsActief", "Jaarduur", "Coordinator_FK", "Fase_FK")));
    $group->get('/{id}', 'App\Controller\OpoController:get');
    $group->put('/{id}', 'App\Controller\OpoController:update')
        ->add(new App\Middleware\ValidateRequestMiddleware(array("Code", "Naam", "Studiepunten", "IsActief", "Jaarduur", "Coordinator_FK", "Fase_FK")));

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
