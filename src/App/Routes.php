<?php

declare(strict_types=1);

use App\Middleware\ValidateRequestMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/opo', function (RouteCollectorproxy $opoGroup) {
    $opoGroup->get('', 'App\Controller\OpoController:getAll'); //Get all OPO's
    $opoGroup->post('', 'App\Controller\OpoController:create') //Create new OPO
        ->add(new ValidateRequestMiddleware(['Code', 'Naam', 'Studiepunten', 'IsActief', 'Jaarduur', 'Jaar', 'Fase_FK']));

    $opoGroup->group('/{id:[0-9]+}', function (RouteCollectorproxy $opoIdGroup) {
        $opoIdGroup->get('', 'App\Controller\OpoController:get'); // Get an OPO
        $opoIdGroup->delete('', 'App\Controller\OpoController:delete'); //Delete an OPO
        $opoIdGroup->put('', 'App\Controller\OpoController:update') // Update an OPO
            ->add(new ValidateRequestMiddleware(['Code', 'Naam', 'Studiepunten', 'IsActief', 'Jaarduur', 'Jaar', 'Fase_FK']));

        $opoIdGroup->put('/coordinator/{coordinatorid}', 'App\Controller\OpoController:addCoordinator'); //Add existing Employee to OPO
        $opoIdGroup->delete('/coordinator/{coordinatorid}', 'App\Controller\OpoController:removeCoordinator'); //Remove coordinator from OPO

        $opoIdGroup->post('/ola', 'App\Controller\OlaController:createUnderOpo') //Create OLA under OPO
            ->add(new ValidateRequestMiddleware(['Code', 'Naam', 'Studiepunten', 'IsActief', 'Jaar', 'Jaarduur']));
        $opoIdGroup->put('/ola', 'App\Controller\OpoController:addOla'); //Add existing OLA to existing OPO
        $opoIdGroup->delete('/ola/{olaid:[0-9]+}', 'App\Controller\OpoController:removeOla'); //remove OLA from OPO

        $opoIdGroup->put('/volgtijdelijkheid', 'App\Controller\OpoController:addConditionalOpo')
            ->add(new ValidateRequestMiddleware(['ConditionalIds']));

        $opoIdGroup->delete('/volgtijdelijkheid', 'App\Controller\OpoController:removeConditionalOpo')
            ->add(new ValidateRequestMiddleware(['ConditionalIds']));
    });
});

$app->group('/ola', function (RouteCollectorProxy $olaGroup) {
    $olaGroup->get('', 'App\Controller\OlaController:getAll'); //Get all OLA's
    $olaGroup->post('', 'App\Controller\OlaController:create') //Create an Ola
        ->add(new ValidateRequestMiddleware(['Code', 'Naam', 'Studiepunten', 'IsActief', 'Jaar', 'Jaarduur']));

    $olaGroup->group('/{id:[0-9]+}', function (RouteCollectorproxy $olaIdGroup) {
        $olaIdGroup->get('', 'App\Controller\OlaController:get'); //Get an OLA
        $olaIdGroup->delete('', 'App\Controller\OlaController:delete'); //Delete an OLA
        $olaIdGroup->put('', 'App\Controller\OlaController:update') //update an OLA
            ->add(new ValidateRequestMiddleware(['Code', 'Naam', 'Studiepunten', 'IsActief', 'Jaar', 'Jaarduur']));
        $olaIdGroup->put('/docent', 'App\Controller\OlaController:addDocent') //Add existing employees to Ola
            ->add(new ValidateRequestMiddleware(['DocentenIds']));

        $olaIdGroup->delete('/docent/{docentid}', 'App\Controller\OlaController:removeDocent'); //Remove docent from OLA
    });
});

$app->group('/personeel', function (RouteCollectorProxy $personeelGroup) {
    $personeelGroup->get('', 'App\Controller\PersoneelController:getAll'); //Get all employees
    $personeelGroup->post('', 'App\Controller\PersoneelController:create') //Create an employee
        ->add(new ValidateRequestMiddleware(['Id', 'Voornaam', 'Achternaam', 'Email', 'GSM']));

    $personeelGroup->group('/{id}', function (RouteCollectorproxy $personeelIdGroup) {
        $personeelIdGroup->get('', 'App\Controller\PersoneelController:get'); //Get an employee
        $personeelIdGroup->delete('', 'App\Controller\PersoneelController:delete'); //Delete an employee
        $personeelIdGroup->put('', 'App\Controller\PersoneelController:update') //update an employee
            ->add(new ValidateRequestMiddleware(['Voornaam', 'Achternaam', 'Email', 'GSM']));
    });
});

$app->group('/student', function (RouteCollectorProxy $studentGroup) {
    $studentGroup->get('', 'App\Controller\StudentController:getAll'); //Get all students
    $studentGroup->post('', 'App\Controller\StudentController:create') //Add new student
        ->add(new ValidateRequestMiddleware(['Student_NR', 'Voornaam', 'Achternaam', 'Email', 'GSM', 'Contract', 'Traject', 'Afstudeerbaar', 'Soort', 'Inschrijvingsjaar']));

    $studentGroup->group('/{id}', function (RouteCollectorproxy $studentIdGroup) {
        $studentIdGroup->get('', 'App\Controller\StudentController:get'); // Get a student
        $studentIdGroup->delete('', 'App\Controller\StudentController:delete'); //Delete a student
        $studentIdGroup->put('', 'App\Controller\StudentController:update') // Update a student
            ->add(new ValidateRequestMiddleware(['Voornaam', 'Achternaam', 'Email', 'GSM', 'Contract', 'Traject', 'Afstudeerbaar', 'Soort', 'Inschrijvingsjaar']));

        $studentIdGroup->post('/opo', 'App\Controller\StudentController:registerInOpo') //Register a student with an OPO
            ->add(new ValidateRequestMiddleware(['OPO_Id_FK', 'Status', 'Jaar']));;

        $studentIdGroup->put('/opo/{opoid:[0-9]+}', 'App\Controller\StudentController:updateRegistration') //Update student registration
            ->add(new ValidateRequestMiddleware(['Status', 'Jaar']));

        $studentIdGroup->delete('/opo/{opoid:[0-9]+}', 'App\Controller\StudentController:unregisterFromOpo') //De-register a student from an OPO
            ->add(new ValidateRequestMiddleware(['Jaar']));

        $studentIdGroup->post('/opo/{opoid:[0-9]+}/vrijstelling', 'App\Controller\StudentController:addExemption') //Add exemptions to registration
            ->add(new ValidateRequestMiddleware(['OLA_Id_FK', 'Jaar']));

        $studentIdGroup->delete('/opo/{opoid:[0-9]+}/vrijstelling', 'App\Controller\StudentController:removeExemption') //Remove exemptions from registration
            ->add(new ValidateRequestMiddleware(['OLA_Id_FK', 'Jaar']));
    });
});
