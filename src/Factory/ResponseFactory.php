<?php

declare(strict_types=1);

namespace App\Factory;

use Slim\Psr7\Response;

final class ResponseFactory
{
    public function buildOKResponse($data): Response
    {
        $response = new Response();
        $body = json_encode(['data' => $data]);

        $response->getBody()->write($body);
        $response->withProtocolVersion('2.0');
        $response->withStatus(200);
        return $response;
    }

    public function buildOKResponseWithMessage($message): Response
    {
        $response = new Response();
        $body = json_encode(['message' => $message]);

        $response->getBody()->write($body);
        $response->withProtocolVersion('2.0');
        $response->withStatus(200);
        return $response;
    }

    public function buildOKResponseWithDataAndMessage($data, $message): Response
    {
        $response = new Response();
        $body = json_encode(['data' => $data, 'message' => $message]);

        $response->getBody()->write($body);
        $response->withProtocolVersion('2.0');
        $response->withStatus(200);
        return $response;
    }

    public function buildErrorResponse($message): Response
    {
        $response = new Response();

        if (!$message || $message === '') {
            $message = 'Sorry, an unknown error occured. Please contact the administrator';
        }

        $body = json_encode(['message' => $message]);

        $response->getBody()->write($body);
        $response->withProtocolVersion('2.0');
        $response->withStatus(400);
        return $response;
    }

    public function buildNotFoundResponse(): Response
    {
        $response = new Response();
        $body = ['Message' => 'The endpoint you are trying to access does not exist. Double check URI & HTTP Method'];

        $response->getBody()->write(json_encode($body));
        $response->withProtocolVersion('2.0');
        $response->withStatus(404);
        return $response;
    }

    public function buildNotAllowedResponse(): Response
    {
        $response = new Response();
        $body = ['Message' => 'You are not authorized to access to access this endpoint or data!'];
        $response->getBody()->write(json_encode($body));
        $response->withProtocolVersion('2.0');
        $response->withStatus(405);
        return $response;
    }
}
