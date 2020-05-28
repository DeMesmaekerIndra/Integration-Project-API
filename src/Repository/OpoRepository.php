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

    public function getByOla($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM OPOs WHERE Id IN (SELECT OPO_Id_FK FROM `OPOs-OLAs` WHERE OLA_Id_FK = :Id)");
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM OPOs");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($body)
    {
        $stmt = $this->connection->prepare("INSERT INTO OPOs (Code, Naam, Studiepunten, IsActief, Jaarduur, Fase_FK ) VALUES (:Code, :Naam, :Studiepunten, :IsActief, :Jaarduur, :Fase_FK)");
        $stmt->bindParam(':Code', $body['Code'], PDO::PARAM_STR);
        $stmt->bindParam(':Naam', $body['Naam'], PDO::PARAM_STR);
        $stmt->bindParam(':Studiepunten', $body['Studiepunten'], PDO::PARAM_INT);
        $stmt->bindParam(':IsActief', $body['IsActief'], PDO::PARAM_BOOL);
        $stmt->bindParam(':Jaarduur', $body['Jaarduur'], PDO::PARAM_STR);
        $stmt->bindParam(':Fase_FK', $body['Fase_FK'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            return false;
        }

        return $this->connection->lastInsertId();
    }

    public function update($body, $id)
    {
        $stmt = $this->connection->prepare("UPDATE OPOs SET Code=:Code, Naam=:Naam, Studiepunten=:Studiepunten, IsActief=:IsActief, Jaarduur=:Jaarduur, Fase_FK=:Fase_FK WHERE Id=:Id");
        $stmt->bindParam(':Code', $body['Code'], PDO::PARAM_STR);
        $stmt->bindParam(':Naam', $body['Naam'], PDO::PARAM_STR);
        $stmt->bindParam(':Studiepunten', $body['Studiepunten'], PDO::PARAM_INT);
        $stmt->bindParam(':IsActief', $body['IsActief'], PDO::PARAM_BOOL);
        $stmt->bindParam(':Jaarduur', $body['Jaarduur'], PDO::PARAM_STR);
        $stmt->bindParam(':Fase_FK', $body['Fase_FK'], PDO::PARAM_INT);
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM OPOs WHERE Id = :Id");
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function addOla($opoId, $olaId)
    {
        $stmt = $this->connection->prepare("INSERT INTO `OPOs-OLAs` (OPO_Id_FK, OLA_Id_FK) VALUES (:OPO_Id_FK, :OLA_Id_FK)");
        $stmt->bindParam(':OPO_Id_FK', $opoId, PDO::PARAM_INT);
        $stmt->bindParam(':OLA_Id_FK', $olaId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function addCoordinator($opoId, $coordinatorId, $body)
    {
        $stmt = $this->connection->prepare("INSERT INTO `opos-onderwijspersoneel` (OPO_Id_FK, Coordinator_Id_FK, Toewijzingsdatum) VALUES (:OPO_Id_FK, :Coordinator_Id_FK, :Toewijzingsdatum)");
        $stmt->bindParam(':OPO_Id_FK', $opoId, PDO::PARAM_INT);
        $stmt->bindParam(':Coordinator_Id_FK', $olcoordinatorIdaId, PDO::PARAM_STR);
        $stmt->bindParam(':Toewijzingsdatum', $body['Toewijzingsdatum'], PDO::PARAM_STR);
        return $stmt->execute();
    }
}
