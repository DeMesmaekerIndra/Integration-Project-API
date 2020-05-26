<?php

declare (strict_types = 1);

use Slim\Routing\RouteCollectorProxy;
$app->group('/opo', function (RouteCollectorproxy $group) {
    $group->get('/{id}', 'App\Controller\OpoController:get');
    $group->get('/', 'App\Controller\OpoController:getAll');

});
