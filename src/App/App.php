<?php

declare (strict_types = 1);

use DI\container;
use Slim\Factory\AppFactory;

final class App
{
    public function getApp()
    {
        require __DIR__ . '/../../vendor/autoload.php';
        $baseDir = __DIR__ . '../../';

        $settings = require __DIR__ . '/Settings.php';
        $container = new Container($settings);

        AppFactory::setContainer($container);
        $app = AppFactory::create();

        $app->setBasePath(''); // Root path, routes will be relative to this
        $app->addBodyParsingMiddleware(); //Support XAML/JSON

        $displayError = filter_var(getenv('DISPLAY_ERROR_DETAILS'), FILTER_VALIDATE_BOOLEAN);

    }
}
