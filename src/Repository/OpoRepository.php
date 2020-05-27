<?php

namespace App\Repository;

use Pimple\Psr11\Container;
use Psr\Container\ContainerInterface;

final class OpoRepository
{
    private $connection;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->connection = $container->get('pdo');
    }

    public function get($id)
    {
        $query = $this->connection->prepare("SELECT * FROM OPOs WHERE Id = (?)");
        $query->execute([$id]);
        return $query->fetchAll();
    }

    public function getAll()
    {
        $query = $this->connection->prepare("SELECT * FROM OPOs");
        $query->execute();
        return $query->fetchAll();
    }

    public function create($newOpo)
    {
        
    }
}
