<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidateRequestMiddleware implements MiddlewareInterface
{
    private $requiredKeys;

    public function __construct(array $requiredKeys)
    {
        $this->requiredKeys = $requiredKeys;
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $postData = $request->getParsedBody();
        $response = new Response();

        if (!$postData) {
            $response->getBody()->write('No data! Check your request data.');
            return $response->withStatus(400);
        }

        foreach ($this->requiredKeys as $key) {
            if (!isset($postData[$key])) {
                $response->getBody()->write('Not enough data! Check your request data.');
                return $response->withStatus(400);
            }
        }

        return $handler->handle($request);
    }
}
