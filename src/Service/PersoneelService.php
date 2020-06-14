<?php

namespace App\Service;

use Psr\Container\ContainerInterface;

final class PersoneelService
{
    private $personeelRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->personeelRepository = $container->get('PersoneelRepository');
    }

    public function get($id): ?iterable
    {
        $personeel = $this->personeelRepository->get($id);
        return $personeel;
    }

    public function getAll(): ?iterable
    {
        $personeel = $this->personeelRepository->getAll();
        return $personeel;
    }

    public function getByOpo($id): ?iterable
    {
        $personeel = $this->personeelRepository->getByOpo($id);
        return $personeel;
    }

    public function getByOla($id): ?iterable
    {
        $personeel = $this->personeelRepository->getByOla($id);
        return $personeel;
    }

    public function create($body): boolean
    {
        $isSuccess = $this->personeelRepository->create($body);
        return $isSuccess;
    }

    public function update($id, $body): boolean
    {
        $isSuccess = $this->personeelRepository->update($id, $body);
        return $isSuccess;
    }

    public function delete($id): boolean
    {
        $isSuccess = $this->personeelRepository->delete($id);
        return $isSuccess;
    }
}
