<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class InschrijvingsController extends BaseController
{
    private $inschrijvingsService;
    private $responseFactory;

    public function __construct(ContainerInterface $container)
    {
        $this->inschrijvingsService = $container->get('InschrijvingsService');
        $this->responseFactory = $container->get('ResponseFactory');
    }
}
