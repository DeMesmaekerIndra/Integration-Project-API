<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class StudentController extends BaseController
{
    private $studentService;
    private $opoService;
    private $olaService;
    private $responseFactory;

    public function __construct(ContainerInterface $container)
    {
        $this->studentService = $container->get('StudentService');
        $this->opoService = $container->get('OpoService');
        $this->olaService = $container->get('OlaService');
        $this->responseFactory = $container->get('ResponseFactory');
    }

    public function get(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $qsParams = $request->getQueryParams();
        $result = $this->studentService->get($id);

        if ($this->findQsParamValue($qsParams, 'o') === 'true') {
            $result['OPOs'] = $this->opoService->getByStudent($id);
        }

        if ($this->findQsParamValue($qsParams, 'e') === 'true') {
            $result['Exemptions'] = $this->olaService->getExemptions($id);
        }

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Could not find student with id: $id");
        }

        return $this->responseFactory->buildOKResponse($result);
    }

    public function getAll(Request $request, Response $response, $args): Response
    {
        $result = $this->studentService->getAll();

        if (!$result) {
            return $this->responseFactory->buildErrorResponse('Could not retrieve students');
        }

        return $this->responseFactory->buildOKResponse($result);
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $resultId = $this->studentService->create($body);

        if (!$resultId || $resultId === 0) {
            return $this->responseFactory->buildErrorResponse('Could not create student');
        }

        return $this->responseFactory->buildOKResponseWithMessage('Student created');
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $body = $request->getParsedBody();
        $result = $this->studentService->update($id, $body);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Student: $id was not updated");
        }

        return $this->responseFactory->buildOKResponseWithMessage("Student: $id was updated");
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $result = $this->studentService->delete($id);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Student: $id was not deleted");
        }

        return $this->responseFactory->buildOKResponseWithMessage("Student: $id was deleted");
    }

    public function registerInOpo(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $body = $request->getParsedBody();
        $opoId = $body['OPO_Id_FK'];
        $result = $this->studentService->registerInOpo($id, $body);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Student: $id was not registered to OPO: $opoId");
        }

        return $this->responseFactory->buildOKResponseWithMessage("Student: $id was registered to OPO: $opoId");
    }

    public function unregisterFromOpo(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $opoId = $args['opoid'];
        $body = $request->getParsedBody();
        $result = $this->studentService->unregisterFromOpo($id, $opoId, $body);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Student: $id was not unregistered from OPO: $opoId");
        }

        return $this->responseFactory->buildOKResponseWithMessage("Student: $id was unregistered from OPO: $opoId");
    }
}
