<?php

declare (strict_types = 1);

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OpoController extends BaseController
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
        $id = $args['id'];

        $result = $this->opoRepository->get($id);

        if ($this->findQsParamValue($qsParams, 'o') === 'true') {
            $result['OLAs'] = $this->olaRepository->getByOpo($id);
        }

        if ($this->findQsParamValue($qsParams, 'c') === 'true') {
            $result['Coordinator'] = $this->PersponeelRepository->getByOpo($id);
        }

        if (!$result) {
            $return = ['Message:' => "Could not find OPO with id: $id"];
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
        $result = $this->opoRepository->getAll();

        if ($this->findQsParamValue($qsParams, 'o') === 'true') {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['OLAs'] = $this->olaRepository->getByOpo($result[$i]['Id']);
            }
        }

        if ($this->findQsParamValue($qsParams, 'c') === 'true') {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['Coordinator'] = $this->PersponeelRepository->getByOpo($result[$i]['Id']);
            }
        }

        if (!$result) {
            $return = ['Message:' => 'Could not retrieve OPOs'];
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = ['data' => $result];
        $response->getBody()->write(json_encode($return));
        return $response->withStatus(200);
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $result = $this->opoRepository->create($body);

        if (!$result) {
            $return = array('Message:' => 'Row was not created');
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => 'Row was created', 'data' => ['Id' => $result]);
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();
        $result = $this->opoRepository->update($body, $args['id']);

        if (!$result) {
            $return = array('Message:' => 'Row was not updated');
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => 'Row was updated');
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        $result = $this->opoRepository->delete($args['id']);

        if (!$result) {
            $return = array('Message:' => 'Row was not deleted');
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => 'Row was deleted');
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);
    }

    public function addOla(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $olaId = $args['olaid'];
        $result = $this->opoRepository->addOla($opoId, $olaId);

        if (!$result) {
            $return = array('Message:' => "Could not link OLA: $olaId with OPO: $opoId");
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => "OLA: $olaId was linked to OPO: $opoId");
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);

    }

    public function addCoordinator(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $coordinatorId = $args['coordinatorid'];
        $body = $request->getParsedBody();
        $result = $this->opoRepository->addCoordinator($opoId, $coordinatorId, $body);

        if (!$result) {
            $return = ['Message:' => "Could not link coordinator: $coordinatorId with OPO: $opoId"];
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = ['Message:' => "Coordinator: $coordinatorId was linked to OPO: $opoId"];
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);
    }
}
