<?php

declare (strict_types = 1);

namespace App\Controller;

use DI\container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class FaseController
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getAll(Request $request, Response $response): Response
    {
        $message = [
            'db info' => $this->container->get('settings'),
        ];
        $return = json_encode($message);
        $response->getBody()->write($return);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
