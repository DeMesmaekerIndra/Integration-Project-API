<?php

declare (strict_types = 1);

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OlaController
{
    private $olaRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->olaRepository = $container->get('OlaRepository');
    }

    public function create(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $newOla = $request->getParsedBody();
        $result = $this->olaRepository->create($newOla, $opoId);

        if (!$result) {
            $return = array('Message:' => 'Row was not created');
            $response->getBody()->write(json_encode($return));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $return = array('Message:' => 'Row was created', "data" => ["Id" => $result]);
        $response->getBody()->write(json_encode($return));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function getAll(Request $request, Response $response, $args): Response
    {
        $result = $this->olaRepository->getAll();
        $message = ['data' => $result];
        $return = json_encode($message);
        $response->getBody()->write($return);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
