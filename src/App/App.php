<?php

declare (strict_types = 1);

use DI\container;
use Slim\Factory\AppFactory;

final class App
{
    private $inDeveloppment = true;
    public function getApp()
    {
        require __DIR__ . '/../../vendor/autoload.php';
        $baseDir = __DIR__ . '../../';

        //Determine which environment file to use
        $dotenv = new Dotenv\Dotenv($baseDir);
        if ($inDeveloppment) {
            if (file_exists($baseDir . '.development.env')) {
                $dotenv->load();
            } elseif (file_exists($baseDir . '.production.env')) {
                $dotenv->load();
            }
        }
        $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

        //Loads settings based on environment
        $settings = require __DIR__ . '/Settings.php';
        $container = new Container($settings);

        //Create the app
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        //Add services to the app
        $path = getenv('SLIM_BASE_PATH') ?: '';
        $app->setBasePath($path); // Root path, routes will be relative to this
        $app->addBodyParsingMiddleware(); //Support XAML/JSON

        //How are errors displayed?
        $displayError = filter_var(getenv('DISPLAY_ERROR_DETAILS'), FILTER_VALIDATE_BOOLEAN);
        $app->addErrorMiddleware($displayError, true, true);

        //Set up simple CORS Headers
        $app->options('/{routes:.+}', function ($request, $response, $args) {
            return $response;
        });
        $app->add(function ($request, $handler) {
            $response = $handler->handle($request);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE');
        });

        //Load required scripts
        require __DIR__ . '/Routes.php';

        // Catch all 404 not found
        $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response): void {
            throw new Slim\Exception\HttpNotFoundException($request);
        });

        return $app;

    }
}
