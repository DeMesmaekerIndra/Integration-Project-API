<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OlaController extends BaseController
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
        $id = $args['id'];
        $qsParams = $request->getQueryParams();
        $result = $this->olaService->get($id);

        if ($this->findQsParamValue($qsParams, 'o') === 'true') {
            $result['OPOs'] = $this->opoService->getByOla($id);
        }

        if ($this->findQsParamValue($qsParams, 'd') === 'true') {
            $result['docenten'] = $this->personeelService->getByOla($id);
        }

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Could not find OLA with id: $id");
        }

        return $this->responseFactory->buildOKResponse($result);
    }

    public function getAll(Request $request, Response $response, $args): Response
    {
        $qsParams = $request->getQueryParams();
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
            return $this->responseFactory->buildErrorResponse('Could not retrieve OLAs');
        }

        return $this->responseFactory->buildOKResponse($result);
    }

    public function create(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();
        $resultId = $this->olaService->create($body);

        if (!$resultId || $resultId === 0) {
            return $this->responseFactory->buildErrorResponse('Unable to create OLA');
        }

        $result = ['Id' => $resultId];

        return $this->responseFactory->buildOKResponseWithDataAndMessage($result, 'OLA created');
    }

    public function createUnderOpo(Request $request, Response $response, $args): Response
    {
        $opoId = $args['id'];
        $body = $request->getParsedBody();
        $resultId = $this->olaService->createUnderOpo($body, $opoId);

        if (!$resultId || $resultId === 0) {
            return $this->responseFactory->buildErrorResponse("Unable to create OLA under OPO: $opoId");
        }

        $result = ['Id' => $resultId];

        return $this->responseFactory->buildOKResponseWithDataAndMessage($result, "OLA created under OPO: $opoId");
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $body = $request->getParsedBody();
        $result = $this->olaService->update($body, $id);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("OLA: $id was not updated");
        }

        return $this->responseFactory->buildOKResponseWithMessage("OLA: $id was updated.");
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $result = $this->olaService->delete($id);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("OLA: $id was not deleted");
        }

        return $this->responseFactory->buildOKResponseWithMessage("OLA: $id was deleted.");
    }

    public function addDocent(Request $request, Response $response, $args): Response
    {
        $olaId = $args['id'];
        $body = $request->getParsedBody();
        $result = $this->olaService->addDocent($olaId, $body);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Could not link the docent(en) to OLA: $olaId");
        }

        return $this->responseFactory->buildOKResponseWithMessage("Docent(en) linked to OLA: $olaId");
    }

    public function removeDocent(Request $request, Response $response, $args): Response
    {
        $olaId = $args['id'];
        $docentId = $args['docentid'];
        $result = $this->olaService->removeDocent($olaId, $docentId);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Could not remove docent: $docentId from OLA: $olaId");
        }

        return $this->responseFactory->buildOKResponseWithMessage("Docent: $docentId linked to OLA: $olaId");
    }
}
