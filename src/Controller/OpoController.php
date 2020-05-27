<?php

declare (strict_types = 1);

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OpoController
{
    private $opoRepository;
    private $olaRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->opoRepository = $container->get('OpoRepository');
        $this->olaRepository = $container->get('OlaRepository');
    }

    public function get(Request $request, Response $response, $args): Response
    {
        $result = $this->opoRepository->get($args['id']);
        $message = ['data' => $result];
        $return = json_encode($message);
        $response->getBody()->write($return);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function getWithOlas(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $opo = $this->opoRepository->get($id);
        $opo['OLAs'] = $this->olaRepository->getByOpo($id);
        $return = ['data' => $opo];
        $response->getBody()->write(json_encode($return));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function getAll(Request $request, Response $response, $args): Response
    {
        $result = $this->opoRepository->getAll();
        $message = ['data' => $result];
        $return = json_encode($message);
        $response->getBody()->write($return);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function getAllWithOlas(Request $request, Response $response, $args): Response
    {
        $return = ['data' => []];
        $opoList = $this->opoRepository->getAll();

        foreach ($opoList as &$opo) {
            $extendedOpo = $opo;
            $extendedOpo['OLAs'] = $this->olaRepository->getByOpo($extendedOpo['Id']);
            array_push($return['data'], $extendedOpo);
        }

        $response->getBody()->write(json_encode($return));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function create(Request $request, Response $response): Response
    {
        $newOpo = $request->getParsedBody();
        $result = $this->opoRepository->create($newOpo);

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
        $result = $this->opoRepository->update($updatedOpo, $args['id']);

        if (!$result) {
            $return = array('Message:' => 'Row was not updated');
            $response->getBody()->write(json_encode($return));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $return = array('Message:' => 'Row was updated');
        $response->getBody()->write(json_encode($return));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        $result = $this->opoRepository->delete($args['id']);

        if (!$result) {
            $return = array('Message:' => 'Row was not deleted');
            $response->getBody()->write(json_encode($return));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $return = array('Message:' => 'Row was deleted');
        $response->getBody()->write(json_encode($return));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

}
