<?php

namespace App\Service;

use Psr\Container\ContainerInterface;

final class StudentService
{
    private $studentRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->studentRepository = $container->get('StudentRepository');
    }

    public function get($id): ?iterable
    {
        $student = $this->studentRepository->get($id);

        if (!$student) {
            return null;
        }

        return $student;
    }

    public function getAll(): ?iterable
    {
        $student = $this->studentRepository->getAll();

        if (!$student) {
            return null;
        }

        return $student;
    }

    public function create($body): bool
    {
        $isSuccess = $this->studentRepository->create($body);
        return $isSuccess;
    }

    public function update($id, $body): bool
    {
        $isSuccess = $this->studentRepository->update($id, $body);
        return $isSuccess;
    }

    public function delete($id): bool
    {
        $isSuccess = $this->studentRepository->delete($id);
        return $isSuccess;
    }
}