<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Container\ContainerInterface;

final class OpoService
{
    private $opoRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->opoRepository = $container->get('OpoRepository');
    }

    public function get($id): ?iterable
    {
        $opo = $this->opoRepository->get($id);

        if (!$opo) {
            return null;
        }

        return $opo;
    }

    public function getByOla($id): ?iterable
    {
        $opos = $this->opoRepository->getByOla($id);

        if (!$opos) {
            return null;
        }

        return $opos;
    }

    public function getAll(): ?iterable
    {
        $opos = $this->opoRepository->getAll();

        if (!$opos) {
            return null;
        }

        return $opos;
    }

    public function create($body): int
    {
        $id = $this->opoRepository->create($body);

        if (!$id) {
            $id = 0;
        }

        return (int) $id;
    }

    public function update($body, $id): bool
    {
        $isSucces = $this->opoRepository->update($body, $id);
        return $isSucces;
    }

    public function delete($id): bool
    {
        $isSucces = $this->opoRepository->delete($id);
        return $isSucces;
    }

    public function addOla($opoId, $olaId): bool
    {
        $isSucces = $this->opoRepository->addOla($opoId, $olaId);
        return $isSucces;
    }

    public function removeOla($opoId, $olaId): bool
    {
        $isSucces = $this->opoRepository->removeOla($opoId, $opoId);
        return $isSucces;
    }

    public function addCoordinator($opoId, $coordinatorId, $body): bool
    {
        $isSucces = $this->opoRepository->addCoordinator($opoId, $coordinatorId, $body);
        return $isSucces;
    }

    public function removeCoordinator($opoId, $coordinatorId): bool
    {
        $isSucces = $this->opoRepository->removeCoordinator($opoId, $coordinatorId);
        return $isSucces;
    }

    public function addConditionalOpo($opoId, $body): bool
    {
        $isSucces = $this->opoRepository->addConditionalOpo($opoId, $body);
        return $isSucces;
    }
}
