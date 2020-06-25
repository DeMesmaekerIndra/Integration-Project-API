<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OpoController extends BaseController
{
    private $opoService;
    private $olaService;
    private $personeelService;
    private $responseFactory;

    public function __construct(ContainerInterface $container)
    {
        $this->opoService = $container->get('OpoService');
        $this->olaService = $container->get('OlaService');
        $this->personeelService = $container->get('PersoneelService');
        $this->responseFactory = $container->get('ResponseFactory');
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

        if ($this->findQsParamValue($qsParams, 'v') === 'true') {
            $result['Conditionals'] = $this->opoService->getConditionels($id);
        }

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Could not find OPO with id: $id");
        }

        return $this->responseFactory->buildOKResponse($result);
    }

    public function getAll(Request $request, Response $response, $args): Response
    {
        $qsParams = $request->getQueryParams();

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
            return $this->responseFactory->buildErrorResponse('Could not retrieve OPOs');
        }

        return $this->responseFactory->buildOKResponse($result);
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $resultId = $this->opoService->create($body);

        if (!$resultId || $resultId === 0) {
            return $this->responseFactory->buildErrorResponse('Unable to create OPO');
        }

        $result = ['Id' => $resultId];

        return $this->responseFactory->buildOKResponseWithDataAndMessage($result, 'OPO created');
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $body = $request->getParsedBody();
        $result = $this->opoService->update($body, $id);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("OPO: $id was not updated");
        }

        return $this->responseFactory->buildOKResponseWithMessage("OPO: $id was updated.");
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $result = $this->opoService->delete($id);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("OPO: $id was not deleted");
        }

        return $this->responseFactory->buildOKResponseWithMessage("OPO: $id was deleted.");
    }

    public function addOla(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $body = $request->getParsedBody();
        $result = $this->opoService->addOla($opoId, $body);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Could not link OLAs to OPO: $opoId");
        }

        return $this->responseFactory->buildOKResponseWithMessage("OLAs were linked to OPO: $opoId");
    }

    public function removeOla(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $olaId = $args['olaid'];
        $result = $this->opoService->removeOla($opoId, $olaId);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Could not unlink OLA: $olaId from OPO: $opoId");
        }

        return $this->responseFactory->buildOKResponseWithMessage("OLA: $olaId was unlinked from OPO: $opoId");
    }

    public function addCoordinator(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $coordinatorId = $args['coordinatorid'];
        $body = $request->getParsedBody();
        $result = $this->opoService->addCoordinator($opoId, $coordinatorId, $body);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Could not link coordinator: $coordinatorId to OPO: $opoId");
        }

        return $this->responseFactory->buildOKResponseWithMessage("Coordinator: $coordinatorId was linked to OPO: $opoId");
    }

    public function removeCoordinator(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $coordinatorId = $args['coordinatorid'];
        $result = $this->opoService->removeCoordinator($opoId, $coordinatorId);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Could not unlink coordinator: $coordinatorId from OPO: $opoId");
        }

        return $this->responseFactory->buildOKResponseWithMessage("Coordinator: $coordinatorId was unlinked from OPO: $opoId");
    }

    public function addConditionalOpo(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $body = $request->getParsedBody();

        $result = $this->opoService->addConditionalOpo($opoId, $body);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Could not link the conditional OPO(s) to OPO: $opoId");
        }

        return $this->responseFactory->buildOKResponseWithMessage("Linked the conditional OPO(s) to OPO: $opoId");
    }

    public function removeConditionalOpo(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $body = $request->getParsedBody();

        $result = $this->opoService->removeConditionalOpo($opoId, $body);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Could not unlink the conditional OPO(s) to OPO: $opoId");
        }

        return $this->responseFactory->buildOKResponseWithMessage("Unlinked the conditional OPO(s) to OPO: $opoId");
    }
}
