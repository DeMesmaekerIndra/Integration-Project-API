<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Container\ContainerInterface;

final class OlaService
{
    private $olaRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->olaRepository = $container->get('OlaRepository');
    }

    public function get($id): ?iterable
    {
        $ola = $this->olaRepository->get($id);

        if (!$ola) {
            return null;
        }

        return $ola;
    }

    public function getByOpo($id): ?iterable
    {
        $olas = $this->olaRepository->getByOpo($id);

        if (!$olas) {
            return null;
        }

        return $olas;
    }

    public function getAll(): ?iterable
    {
        $olas = $this->olaRepository->getAll();

        if (!$olas) {
            return null;
        }

        return $olas;
    }

    public function create($body): int
    {
        $id = $this->olaRepository->create($body);

        if (!$id) {
            $id = 0;
        }

        return (int) $id;
    }

    public function createUnderOpo($body, $opoId): int
    {
        $id = $this->olaRepository->createUnderOpo($body, $opoId);

        if (!$id) {
            $id = 0;
        }

        return (int) $id;
    }

    public function update($body, $id): bool
    {
        $isSuccess = $this->olaRepository->update($body, $id);
        return $isSuccess;
    }

    public function delete($id): bool
    {
        $isSuccess = $this->olaRepository->delete($id);
        return $isSuccess;
    }

    public function addDocent($olaId, $body): bool
    {
        $isSuccess = $this->olaRepository->addDocent($olaId, $body);
        return $isSuccess;
    }

    public function removeDocent($olaId, $docentId): bool
    {
        $isSuccess = $this->olaRepository->removeDocent($olaId, $docentId);
        return $isSuccess;
    }
}
