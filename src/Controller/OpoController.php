<?php

declare (strict_types = 1);

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OpoController
{
    private $repo;

    public function __construct(ContainerInterface $container)
    {
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
        $result = $this->repo->create($newOpo);

        if (!$result) {
            $return = array('Message:' => 'Row was not created');
            $response->getBody()->write(json_encode($return));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $return = array('Message:' => 'Row was created');
        $response->getBody()->write(json_encode($return));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $updatedOpo = $request->getParsedBody();
        $result = $this->repo->update($updatedOpo, $args['id']);

        if (!$result) {
            $return = array('Message:' => 'Row was not created');
            $response->getBody()->write(json_encode($return));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $return = array('Message:' => 'Row was created');
        $response->getBody()->write(json_encode($return));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
