<?php

declare (strict_types = 1);

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OlaController extends BaseController
{
    private $opoRepository;
    private $olaRepository;
    private $PersponeelRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->opoRepository = $container->get('OpoRepository');
        $this->olaRepository = $container->get('OlaRepository');
        $this->PersponeelRepository = $container->get('PersoneelRepository');
    }

    public function get(Request $request, Response $response, $args): Response
    {
        $qsParams = $request->getQueryParams();
        $result = $this->olaRepository->get($args['id']);

        if ($this->findQsParamValue($qsParams, 'o') === 'true') {
            $result['OPOs'] = $this->opoRepository->getByOla($args['id']);
        }

        if ($this->findQsParamValue($qsParams, 'd') === 'true') {
            $result['docenten'] = $this->PersponeelRepository->getByOla($id);
        }

        if (!$result) {
            $return = ['Message:' => "Could not find OLA with id: $id"];
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $message = ['data' => $result];
        $return = json_encode($message);
        $response->getBody()->write($return);
        return $response->withStatus(200);
    }

    public function getAll(Request $request, Response $response, $args): Response
    {
        $qsParams = $request->getQueryParams();
        $return = ['data' => []];
        $result = $this->olaRepository->getAll();

        if ($this->findQsParamValue($qsParams, 'o') === 'true') {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['OPOs'] = $this->opoRepository->getByOla($result[$i]['Id']);
            }
        }

        if (!$result) {
            $return = ['Message:' => "Could not retrieve OLAs"];
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = ['data' => $result];

        $response->getBody()->write(json_encode($return));
        return $response->withStatus(200);
    }

    //TODO: testing
    public function create(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();
        $result = $this->olaRepository->create($body);

        if (!$result) {
            $return = array('Message:' => 'Row was not created');
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => 'Row was created', "data" => ["Id" => $result]);
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);
    }

    //TODO: testing
    public function createUnderOpo(Request $request, Response $response, $args): Response
    {
        $olaId = $args['id'];
        $body = $request->getParsedBody();
        $result = $this->olaRepository->createUnderOpo($body, $olaId);

        if (!$result) {
            $return = array('Message:' => 'Row was not created');
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => 'Row was created', "data" => ["Id" => $result]);
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);

    }

    //TODO: testing
    public function update(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();
        $result = $this->olaRepository->update($body, $args['id']);

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
        $result = $this->olaRepository->delete($args['id']);

        if (!$result) {
            $return = array('Message:' => 'Row was not deleted');
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => 'Row was deleted');
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);
    }

    //TODO: testing
    public function addDocent(Request $request, Response $response, $args): Response
    {
        $olaId = $args['id'];
        $body = $request->getParsedBody();
        $result = $this->olaRepository->addDocent($olaId, $body);

        if (!$result) {
            $return = ['Message:' => "Could not link the docent(en) to OLA: $olaId"];
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = ['Message:' => "Docent(en) linked to OLA: $olaId"];
        $response->getBody()->write(json_encode($return));
        return $response->withStatus(200);
    }
}
