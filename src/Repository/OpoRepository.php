<?php

declare(strict_types=1);

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
        $stmt = $this->connection->prepare("SELECT * FROM opos WHERE Id = :Id");
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getByOla($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM opos WHERE Id IN (SELECT OPO_Id_FK FROM `opos-olas` WHERE OLA_Id_FK = :Id)");
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM opos");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($body)
    {
        $stmt = $this->connection->prepare("INSERT INTO opos (Code, Naam, Studiepunten, IsActief, Jaarduur, Fase_FK ) VALUES (:Code, :Naam, :Studiepunten, :IsActief, :Jaarduur, :Fase_FK)");
        $stmt->bindParam(':Code', $body['Code'], PDO::PARAM_STR);
        $stmt->bindParam(':Naam', $body['Naam'], PDO::PARAM_STR);
        $stmt->bindParam(':Studiepunten', $body['Studiepunten'], PDO::PARAM_INT);
        $stmt->bindParam(':IsActief', $body['IsActief'], PDO::PARAM_INT);
        $stmt->bindParam(':Jaarduur', $body['Jaarduur'], PDO::PARAM_STR);
        $stmt->bindParam(':Fase_FK', $body['Fase_FK'], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            return false;
        }

        return $this->connection->lastInsertId();
    }

    public function update($body, $id)
    {
        $stmt = $this->connection->prepare("UPDATE opos SET Code=:Code, Naam=:Naam, Studiepunten=:Studiepunten, IsActief=:IsActief, Jaarduur=:Jaarduur, Fase_FK=:Fase_FK WHERE Id=:Id");
        $stmt->bindParam(':Code', $body['Code'], PDO::PARAM_STR);
        $stmt->bindParam(':Naam', $body['Naam'], PDO::PARAM_STR);
        $stmt->bindParam(':Studiepunten', $body['Studiepunten'], PDO::PARAM_INT);
        $stmt->bindParam(':IsActief', $body['IsActief'], PDO::PARAM_INT);
        $stmt->bindParam(':Jaarduur', $body['Jaarduur'], PDO::PARAM_STR);
        $stmt->bindParam(':Fase_FK', $body['Fase_FK'], PDO::PARAM_INT);
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM opos WHERE Id = :Id");
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function addOla($opoId, $olaId)
    {
        $stmt = $this->connection->prepare("INSERT INTO `opos-olas` (OPO_Id_FK, OLA_Id_FK) VALUES (:OPO_Id_FK, :OLA_Id_FK)");
        $stmt->bindParam(':OPO_Id_FK', $opoId, PDO::PARAM_INT);
        $stmt->bindParam(':OLA_Id_FK', $olaId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function removeOla($opoId, $olaId)
    {
        $stmt = $this->connection->prepare("DELETE FROM `opos-olas` WHERE OPO_Id_FK = :OPO_Id_FK AND OLA_Id_FK = :OLA_Id_FK");
        $stmt->bindParam(':OPO_Id_FK', $opoId, PDO::PARAM_INT);
        $stmt->bindParam(':OLA_Id_FK', $olaId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function addCoordinator($opoId, $coordinatorId, $body)
    {
        $stmt = $this->connection->prepare("INSERT INTO `opos-onderwijspersoneel` (OPO_Id_FK, Coordinator_Id_FK, Toewijzingsdatum) VALUES (:OPO_Id_FK, :Coordinator_Id_FK, :Toewijzingsdatum)");
        $stmt->bindParam(':OPO_Id_FK', $opoId, PDO::PARAM_INT);
        $stmt->bindParam(':Coordinator_Id_FK', $coordinatorId, PDO::PARAM_STR);
        $stmt->bindParam(':Toewijzingsdatum', $body['Toewijzingsdatum'], PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function removeCoordinator($opoId, $coordinatorId)
    {
        $stmt = $this->connection->prepare("DELETE FROM `opos-onderwijspersoneel` WHERE Coordinator_Id_FK = :Coordinator_Id_FK AND OPO_Id_FK = :OPO_Id_FK");
        $stmt->bindParam(':OPO_Id_FK', $opoId, PDO::PARAM_INT);
        $stmt->bindParam(':Coordinator_Id_FK', $coordinatorId, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function addConditionalOpo($opoId, $body)
    {
        $this->connection->beginTransaction();

        foreach ($body['ConditionalIds'] as &$conditionalId) {
            $stmt = $this->connection->prepare("INSERT INTO `volgtijdelijkheden` (OPO_Id, Voorwaarde_OPO_Id) VALUES (:OPO_Id, :Voorwaarde_OPO_Id)");
            $stmt->bindParam(':OPO_Id', $opoId, PDO::PARAM_INT);
            $stmt->bindParam(':Voorwaarde_OPO_Id', $conditionalId, PDO::PARAM_STR);
            if (!$stmt->execute()) {
                $this->connection->rollback();
                return false;
            }
        }

        $this->connection->commit();
        return true;
    }
}
