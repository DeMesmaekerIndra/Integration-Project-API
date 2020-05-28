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

    public function __construct(ContainerInterface $container)
    {
        $this->opoRepository = $container->get('OpoRepository');
        $this->olaRepository = $container->get('OlaRepository');
    }

    public function get(Request $request, Response $response, $args): Response
    {
        $qsParams = $request->getQueryParams();
        $id = $args['id'];

        $result = $this->opoRepository->get($id);

        if ($this->findQsParamValue($qsParams, 'o') === 'true') {
            $result['OLAs'] = $this->olaRepository->getByOpo($id);
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

        $return = ['data' => $result];

        $response->getBody()->write(json_encode($return));
        return $response->withStatus(200);
    }

    public function create(Request $request, Response $response): Response
    {
        $newOpo = $request->getParsedBody();
        $result = $this->opoRepository->create($newOpo);

        if (!$result) {
            $return = array('Message:' => 'Row was not created');
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => 'Row was created');
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $updatedOpo = $request->getParsedBody();
        $result = $this->opoRepository->update($updatedOpo, $args['id']);

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

    public function AddOlaToOpo(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $olaId = $args['olaid'];
        $result = $this->olaRepository->AddOlaToOpo($opoId, $olaId);

        if (!$result) {
            $return = array('Message:' => 'Row was not created');
            $response->getBody()->write(json_encode($return));
            return $response->withStatus(400);
        }

        $return = array('Message:' => "OLA: $olaId was linked to OPO: $opoId");
        $response->getBody()->write(json_encode($return));

        return $response->withStatus(200);

    }
}
