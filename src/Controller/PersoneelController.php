<?php

declare (strict_types = 1);

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class PersoneelController extends BaseController
{
    private $personeelRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->personeelRepository = $container->get('PersoneelRepository');
    }

    //TODO: testing
    public function get(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $result = $this->personeelRepository->get($id);

        if (!$result) {
            $return = ['Message:' => "Could not find employee with id: $id"];
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $message = ['data' => $result];
        $return = json_encode($message);
        $response->getBody()->write($return);
        return $response->withStatus(200);
    }

    //TODO: testing
    public function getAll(Request $request, Response $response, $args): Response
    {
        $result = $this->personeelRepository->getAll();
        $return = ['data' => $result];
        $response->getBody()->write(json_encode($return));
        return $response->withStatus(200);
    }

    //TODO: testing
    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $result = $this->personeelRepository->create($body);

        if (!$result) {
            $return = ['Message:' => 'Row was not created'];
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = ['Message:' => 'Row was created'];
        $response->getBody()->write(json_encode($return));
        return $response->withStatus(200);
    }

    //TODO: testing
    public function update(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $body = $request->getParsedBody();
        $result = $this->personeelRepository->update($id, $body);

        if (!$result) {
            $return = array('Message:' => 'Row was not updated');
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => 'Row was updated');
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);
    }

    //TODO: testing
    public function delete(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $result = $this->personeelRepository->delete($id);

        if (!$result) {
            $return = ['Message:' => 'Row was not deleted'];
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = ['Message:' => 'Row was deleted'];
        $response->getBody()->write(json_encode($return));
        return $response->withStatus(200);
    }
}
