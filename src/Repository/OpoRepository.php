<?php

namespace App\Repository;

use PDO;
use Psr\Container\ContainerInterface;

final class OpoRepository
{
    private $connection;

    public function __construct(ContainerInterface $container)
    {
        $this->connection = $container->get('pdo');
    }

    public function get($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM OPOs WHERE Id = :Id");
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM OPOs");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($newOpo)
    {
        $stmt = $this->connection->prepare("INSERT INTO OPOs (Code, Naam, Studiepunten, IsActief, Jaarduur, Fase_FK ) VALUES (:Code, :Naam, :Studiepunten, :IsActief, :Jaarduur, :Fase_FK)");
        $stmt->bindParam(':Code', $newOpo['Code'], PDO::PARAM_STR);
        $stmt->bindParam(':Naam', $newOpo['Naam'], PDO::PARAM_STR);
        $stmt->bindParam(':Studiepunten', $newOpo['Studiepunten'], PDO::PARAM_INT);
        $stmt->bindParam(':IsActief', $newOpo['IsActief'], PDO::PARAM_BOOL);
        $stmt->bindParam(':Jaarduur', $newOpo['Jaarduur'], PDO::PARAM_STR);
        $stmt->bindParam(':Fase_FK', $newOpo['Fase_FK'], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function update($updatedOpo, $id)
    {
        $stmt = $this->connection->prepare("UPDATE OPOs SET Code=:Code, Naam=:Naam, Studiepunten=:Studiepunten, IsActief=:IsActief, Jaarduur=:Jaarduur, Fase_FK=:Fase_FK WHERE Id=:Id");
        $stmt->bindParam(':Code', $updatedOpo['Code'], PDO::PARAM_STR);
        $stmt->bindParam(':Naam', $updatedOpo['Naam'], PDO::PARAM_STR);
        $stmt->bindParam(':Studiepunten', $updatedOpo['Studiepunten'], PDO::PARAM_INT);
        $stmt->bindParam(':IsActief', $updatedOpo['IsActief'], PDO::PARAM_BOOL);
        $stmt->bindParam(':Jaarduur', $updatedOpo['Jaarduur'], PDO::PARAM_STR);
        $stmt->bindParam(':Fase_FK', $updatedOpo['Fase_FK'], PDO::PARAM_INT);
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM OPOs WHERE Id = :Id");
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
