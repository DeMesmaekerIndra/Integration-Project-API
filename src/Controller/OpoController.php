<?php

declare (strict_types = 1);

namespace App\Controller;

use Pimple\Psr11\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OpoController
{
    private $container;
    private $repo;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->repo = $container->get('OpoRepository');
    }

    public function get(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $Opo = $this->repo->get($id);
        $message = ['data' => $Opo];
        $return = json_encode($message);
        $response->getBody()->write($return);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function getAll(Request $request, Response $response, $args): Response
    {
        $OpoList = $this->repo->getAll();
        $message = ['data' => $OpoList];
        $return = json_encode($message);
        $response->getBody()->write($return);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function create(Request $request, Response $response): Response
    {
        $newOpo = $request->getParsedBody();
        

        $response->getBody()->write('Made it to create method');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    }

    public function update(Request $request, Response $response): Response
    {
        $newOpo = $request->getParsedBody();

        $response->getBody()->write('Made it to update method');
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
