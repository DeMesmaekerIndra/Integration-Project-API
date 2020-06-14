<?php
namespace App\Service;

use Psr\Container\ContainerInterface;

final class OlaService
{
    private $olaRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->olaRepository = $container->get('OlaRepository');
    }

    public function get($id): iterable
    {
        $opo = $this->olaRepository->get($id);
        return $opo;
    }

    public function getByOpo($id): iterable
    {
        $opos = $this->olaRepository->getByOpo($id);
        return $opos;
    }

    public function getAll(): iterable
    {
        $opos = $this->olaRepository->getAll();
        return $opos;
    }

    public function create($body): int
    {
        $id = $this->olaRepository->create($body);
        return $id;
    }

    public function createUnderOpo($body, $opoId): int
    {
        $id = $this->olaRepository->createUnderOpo($body, $opoId);
        return $id;
    }

    public function update($body, $id): boolean
    {
        $isSuccess = $this->olaRepository->update($body, $id);
        return $isSuccess;
    }

    public function delete($id): boolean
    {
        $isSuccess = $this->olaRepository->delete($id);
        return $isSuccess;
    }

    public function addDocent($olaId, $body): boolean
    {
        $isSuccess = $this->olaRepository->addDocent($olaId, $body);
        return $isSuccess;
    }

    public function removeDocent($olaId, $docentId): boolean
    {
        $isSuccess = $this->olaRepository->removeDocent($olaId, $docentId);
        return $isSuccess;
    }
}
