<?php

declare (strict_types = 1);

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OlaController extends BaseController
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
        $result = $this->olaService->get($id);

        if ($this->findQsParamValue($qsParams, 'o') === 'true') {
            $result['OPOs'] = $this->opoService->getByOla($id);
        }

        if ($this->findQsParamValue($qsParams, 'd') === 'true') {
            $result['docenten'] = $this->personeelService->getByOla($id);
        }

        if (!$result) {
            $return = ['Message:' => "Could not find OLA with id: $id"];
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $response->getBody()->write(json_encode($result));
        return $response->withStatus(200);
    }

    public function getAll(Request $request, Response $response, $args): Response
    {
        $qsParams = $request->getQueryParams();
        $return = ['data' => []];
        $result = $this->olaService->getAll();

        if ($this->findQsParamValue($qsParams, 'o') === 'true') {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['OPOs'] = $this->opoService->getByOla($result[$i]['Id']);
            }
        }

        if ($this->findQsParamValue($qsParams, 'd') === 'true') {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['Docenten'] = $this->personeelService->getByOla($result[$i]['Id']);
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

    public function create(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();
        $result = $this->olaService->create($body);

        if (!$result) {
            $return = array('Message:' => 'Row was not created');
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => 'Row was created', "data" => ["Id" => $result]);
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);
    }

    public function createUnderOpo(Request $request, Response $response, $args): Response
    {
        $olaId = $args['id'];
        $body = $request->getParsedBody();
        $result = $this->olaService->createUnderOpo($body, $olaId);

        if (!$result) {
            $return = array('Message:' => 'Row was not created');
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => 'Row was created', "data" => ["Id" => $result]);
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);

    }

    public function update(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();
        $result = $this->olaService->update($body, $args['id']);

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
        $result = $this->olaService->delete($args['id']);

        if (!$result) {
            $return = array('Message:' => 'Row was not deleted');
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => 'Row was deleted');
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);
    }

    public function addDocent(Request $request, Response $response, $args): Response
    {
        $olaId = $args['id'];
        $body = $request->getParsedBody();
        $result = $this->olaService->addDocent($olaId, $body);

        if (!$result) {
            $return = ['Message:' => "Could not link the docent(en) to OLA: $olaId"];
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = ['Message:' => "Docent(en) linked to OLA: $olaId"];
        $response->getBody()->write(json_encode($return));
        return $response->withStatus(200);
    }

    public function removeDocent(Request $request, Response $response, $args): Response
    {
        $olaId = $args['id'];
        $docentId = $args['docentid'];
        $result = $this->olaService->removeDocent($olaId, $docentId);

        if (!$result) {
            $return = ['Message:' => "Could not remove docent: $docentId from OLA: $olaId"];
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = ['Message:' => "Docent: $docentId linked to OLA: $olaId"];
        $response->getBody()->write(json_encode($return));
        return $response->withStatus(200);
    }
}
