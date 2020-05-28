<?php

declare (strict_types = 1);

use App\Middleware\ValidateRequestMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/opo', function (RouteCollectorproxy $opoGroup) {
    $opoGroup->get('', 'App\Controller\OpoController:getAll'); //Get all OPO's
    $opoGroup->post('', 'App\Controller\OpoController:create') //Create new OPO
        ->add(new ValidateRequestMiddleware(array('Code', 'Naam', 'Studiepunten', 'IsActief', 'Jaarduur', 'Fase_FK')));

    $opoGroup->group('/{id:[0-9]+}', function (RouteCollectorproxy $opoIdGroup) {
        $opoIdGroup->get('', 'App\Controller\OpoController:get'); // Get an OPO
        $opoIdGroup->delete('', 'App\Controller\OpoController:delete'); //Delete an OPO
        $opoIdGroup->put('', 'App\Controller\OpoController:update') // Update an OPO
            ->add(new ValidateRequestMiddleware(array('Code', 'Naam', 'Studiepunten', 'IsActief', 'Jaarduur', 'Fase_FK')));

        $opoIdGroup->post('/ola', 'App\Controller\OlaController:createUnderOpo') //Create OLA under OPO
            ->add(new ValidateRequestMiddleware(array('Code', 'Naam', 'Studiepunten', 'IsActief', 'Jaarduur')));
        $opoIdGroup->put('/ola/{olaid:[0-9]+', 'App\Controller\OpoController:AddOlaToOpo'); //Add existing OLA to existing OPO
    });
});

$app->group('/ola', function (RouteCollectorProxy $olaGroup) {
    $olaGroup->get('', 'App\Controller\OlaController:getAll'); //Get all OLA's
    $olaGroup->post('', 'App\Controller\OlaController:create') //Create an Ola
        ->add(new ValidateRequestMiddleware(array('Code', 'Naam', 'Studiepunten', 'IsActief', 'Jaarduur')));

    $olaGroup->group('/{id:[0-9]+}', function (RouteCollectorproxy $olaIdGroup) {
        $olaIdGroup->get('', 'App\Controller\OlaController:get'); //Get an OLA
        $olaIdGroup->delete('', 'App\Controller\OlaController:delete'); //Delete an OLA
        $olaIdGroup->put('', 'App\Controller\OlaController:update') //update an OLA
            ->add(new ValidateRequestMiddleware(array('Code', 'Naam', 'Studiepunten', 'IsActief', 'Jaarduur')));
    });

});

/*
$app->group('/Personeel', function (RouteCollectorproxy $group) {
$group->get('/', 'App\Controller\OpoController:getAll');
$group->get('/{id}', 'App\Controller\OpoController:get');
$group->get('/allByOla/{olaId}', 'App\Controller\OpoController:get');
});*/
