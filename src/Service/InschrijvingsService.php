<?php

namespace App\Service;

use Psr\Container\ContainerInterface;

final class InschrijvingsService
{
    private $inschrijvingsRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->inschrijvingsRepository = $container->get('InschrijvingsRepository');
    }
}
