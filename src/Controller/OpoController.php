<?php

declare (strict_types = 1);

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OpoController extends BaseController
{
    private $opoService;
    private $olaService;
    private $personeelService;

    public function __construct(ContainerInterface $container)
    {
        $this->opoService = $container->get('OpoService');
        $this->olaService = $container->get('OlaService');
        $this->personeelService = $container->get('PersoneelService');

    }

    public function get(Request $request, Response $response, $args): Response
    {
        $qsParams = $request->getQueryParams();
        $id = $args['id'];

        $result = $this->opoService->get($id);

        if ($this->findQsParamValue($qsParams, 'o') === 'true') {
            $result['OLAs'] = $this->olaService->getByOpo($id);
        }

        if ($this->findQsParamValue($qsParams, 'c') === 'true') {
            $result['Coordinator'] = $this->personeelService->getByOpo($id);
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
        $result = $this->opoService->getAll();

        if ($this->findQsParamValue($qsParams, 'o') === 'true') {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['OLAs'] = $this->olaService->getByOpo($result[$i]['Id']);
            }
        }

        if ($this->findQsParamValue($qsParams, 'c') === 'true') {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['Coordinator'] = $this->personeelService->getByOpo($result[$i]['Id']);
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
        $result = $this->opoService->create($body);

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
        $result = $this->opoService->update($body, $args['id']);

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
        $result = $this->opoService->delete($args['id']);

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
        $result = $this->opoService->addOla($opoId, $olaId);

        if (!$result) {
            $return = array('Message:' => "Could not link OLA: $olaId with OPO: $opoId");
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => "OLA: $olaId was linked to OPO: $opoId");
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);

    }

    public function removeOla(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $olaId = $args['olaid'];
        $result = $this->opoService->removeOla($opoId, $olaId);

        if (!$result) {
            $return = array('Message:' => "Could remove OLA: $olaId from OPO: $opoId");
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => "OLA: $olaId was removed from OPO: $opoId");
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);

    }

    public function addCoordinator(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $coordinatorId = $args['coordinatorid'];
        $body = $request->getParsedBody();
        $result = $this->opoService->addCoordinator($opoId, $coordinatorId, $body);

        if (!$result) {
            $return = ['Message:' => "Could not link coordinator: $coordinatorId with OPO: $opoId"];
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = ['Message:' => "Coordinator: $coordinatorId was linked to OPO: $opoId"];
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);
    }

    public function removeCoordinator(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $coordinatorId = $args['coordinatorid'];
        $result = $this->opoService->removeCoordinator($opoId, $coordinatorId);

        if (!$result) {
            $return = ['Message:' => "Could not remove coordinator: $coordinatorId from OPO: $opoId"];
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = ['Message:' => "Coordinator: $coordinatorId was removed from OPO: $opoId"];
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);
    }
}
