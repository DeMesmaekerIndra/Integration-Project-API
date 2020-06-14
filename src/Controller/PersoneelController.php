<?php

declare (strict_types = 1);

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class PersoneelController extends BaseController
{
    private $personeelService;
    private $responseFactory;

    public function __construct(ContainerInterface $container)
    {
        $this->personeelService = $container->get('PersoneelService');
        $this->responseFactory = $container->get('ResponseFactory');
    }

    public function get(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $result = $this->personeelService->get($id);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Could not find employee with id: $id");
        }

        return $this->responseFactory->buildOKResponse($result);
    }

    public function getAll(Request $request, Response $response, $args): Response
    {
        $result = $this->personeelService->getAll();

        if (!$result) {
            return $this->responseFactory->buildErrorResponse('Could not retrieve employees');
        }

        return $this->responseFactory->buildOKResponse($result);
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $resultId = $this->personeelService->create($body);

        if (!$resultId || $resultId === 0) {
            return $this->responseFactory->buildErrorResponse('Could not create employee');
        }

        $result = ['Id' => $resultId];

        return $this->responseFactory->buildOKResponseWithDataAndMessage($result, 'Employee created');
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $body = $request->getParsedBody();
        $result = $this->personeelService->update($id, $body);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Employee: $id was not updated");
        }

        return $this->responseFactory->buildOKResponseWithMessage("Employee: $id was updated");
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $result = $this->personeelService->delete($id);

        if (!$result) {
            return $this->responseFactory->buildErrorResponse("Employee: $id was not deleted");
        }

        return $this->responseFactory->buildOKResponseWithMessage("Employee: $id was deleted");
    }
}
