<?php

declare (strict_types = 1);

use Pimple\Container as PimpleContainer;
use Pimple\Psr11\Container as Psr11Container;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

final class App
{
    private const IN_DEV = true;

    public function getApp()
    {
        require __DIR__ . '/../../vendor/autoload.php';
        $baseDir = __DIR__ . '/../..';

        $dotenv = null;

        //Determine which environment file to use
        if (self::IN_DEV) {
            $dotenv = Dotenv\Dotenv::createImmutable($baseDir, '.dev.env');
            $dotenv->load();
        } else {
            $dotenv = Dotenv\Dotenv::createImmutable($baseDir, '.prod.env');
            $dotenv->load();
        }
        $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

        //Loads settings based on environment
        $settings = require __DIR__ . '/Settings.php';
        $container = new PimpleContainer($settings);

        //Create the app
        $app = AppFactory::create(null, new Psr11Container($container));

        //Add services to the app & configure
        $path = $_SERVER['SLIM_BASE_PATH'] ?: '';
        $app->setBasePath($path); // Root path, routes will be relative to this
        $app->addBodyParsingMiddleware(); //Support XAML/JSON

        //How are errors displayed
        $displayError = filter_var($_SERVER['DISPLAY_ERROR_DETAILS'], FILTER_VALIDATE_BOOLEAN);
        $app->addErrorMiddleware($displayError, true, true);

        //Set up middleware to define headers
        $app->add(function ($request, $handler) {
            $response = $handler->handle($request);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
                ->withHeader('Content-Type', 'application/json');
        });

        //Load required scripts
        require __DIR__ . '/Routes.php';
        require __DIR__ . '/Dependencies.php';
        require __DIR__ . '/Repositories.php';
        require __DIR__ . '/Services.php';
        require __DIR__ . '/Factories.php';

        // Catch all 404 not found
        $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response): Response {
            $return = ['Message' => 'The endpoint you tried to access does not exist. Double check your URI & method!'];
            $response->getbody()->write(json_encode($return));
            return $response->withStatus(200);
        });

        return $app;
    }
}
