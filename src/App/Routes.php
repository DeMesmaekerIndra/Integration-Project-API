<?php

declare (strict_types = 1);

use App\Middleware\ValidateRequestMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/opo', function (RouteCollectorproxy $opoGroup) {
    $opoGroup->get('', 'App\Controller\OpoController:getAll'); //Get all OPO's
    $opoGroup->post('', 'App\Controller\OpoController:create') //Add new OPO
        ->add(new ValidateRequestMiddleware(array("Code", "Naam", "Studiepunten", "IsActief", "Jaarduur", "Fase_FK")));

    $opoGroup->get('/ola', 'App\Controller\OpoController:getAllWithOlas'); //Get all OPO with its OLAs

    $opoGroup->group('/{id:[0-9]+}', function (RouteCollectorproxy $idGroup) {
        $idGroup->get('', 'App\Controller\OpoController:get'); // Get an OPO
        $idGroup->put('', 'App\Controller\OpoController:update') // Update an OPO
            ->add(new ValidateRequestMiddleware(array("Code", "Naam", "Studiepunten", "IsActief", "Jaarduur", "Fase_FK")));

        $idGroup->delete('', 'App\Controller\OpoController:delete');

        $idGroup->get('/ola', 'App\Controller\OpoController:getWithOlas'); //Get OPO with its OLA's
        $idGroup->post('/ola', 'App\Controller\OlaController:create') //Create OLA under OPO
            ->add(new ValidateRequestMiddleware(array("Code", "Naam", "Studiepunten", "IsActief", "Jaarduur")));
    });
});

$app->group('/ola', function (RouteCollectorProxy $group) {
    $group->get('', 'App\Controller\OlaController:getAll');
    /* $group->post('', 'App\Controller\OpoController:create')
->add(new ValidateRequestMiddleware(array("Code", "Naam", "Studiepunten", "IsActief", "Jaarduur", "Coordinator_FK", "Fase_FK")));
$group->get('/{id}', 'App\Controller\OpoController:get');
$group->put('/{id}', 'App\Controller\OpoController:update')
->add(new ValidateRequestMiddleware(array("Code", "Naam", "Studiepunten", "IsActief", "Jaarduur", "Coordinator_FK", "Fase_FK")));*/
});

/*
$app->group('/Personeel', function (RouteCollectorproxy $group) {
$group->get('/', 'App\Controller\OpoController:getAll');
$group->get('/{id}', 'App\Controller\OpoController:get');
$group->get('/allByOla/{olaId}', 'App\Controller\OpoController:get');
});*/
