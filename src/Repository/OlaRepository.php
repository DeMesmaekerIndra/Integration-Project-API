<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;
use Psr\Container\ContainerInterface;

final class OlaRepository
{
    private $connection;

    public function __construct(ContainerInterface $container)
    {
        $this->connection = $container->get('pdo');
    }

    public function get($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM olas WHERE Id = :Id");
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getByOpo($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM olas WHERE Id IN (SELECT OLA_Id_FK FROM `opos-olas` WHERE OPO_Id_FK = :Id)");
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM olas");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($body)
    {
        $stmt = $this->connection->prepare("INSERT INTO olas (Code, Naam, Studiepunten, IsActief, Jaarduur) VALUES (:Code, :Naam, :Studiepunten, :IsActief, :Jaarduur)");
        $stmt->bindParam(':Code', $body['Code'], PDO::PARAM_STR);
        $stmt->bindParam(':Naam', $body['Naam'], PDO::PARAM_STR);
        $stmt->bindParam(':Studiepunten', $body['Studiepunten'], PDO::PARAM_INT);
        $stmt->bindParam(':IsActief', $body['IsActief'], PDO::PARAM_INT);
        $stmt->bindParam(':Jaarduur', $body['Jaarduur'], PDO::PARAM_STR);

        if (!$stmt->execute()) {
            return false;
        }

        $bodyId = $this->connection->lastInsertId();

        return $bodyId;
    }

    public function createUnderOpo($body, $opoId)
    {
        $this->connection->beginTransaction();
        $stmt = $this->connection->prepare("INSERT INTO olas (Code, Naam, Studiepunten, IsActief, Jaarduur) VALUES (:Code, :Naam, :Studiepunten, :IsActief, :Jaarduur)");
        $stmt->bindParam(':Code', $body['Code'], PDO::PARAM_STR);
        $stmt->bindParam(':Naam', $body['Naam'], PDO::PARAM_STR);
        $stmt->bindParam(':Studiepunten', $body['Studiepunten'], PDO::PARAM_INT);
        $stmt->bindParam(':IsActief', $body['IsActief'], PDO::PARAM_INT);
        $stmt->bindParam(':Jaarduur', $body['Jaarduur'], PDO::PARAM_STR);

        if (!$stmt->execute()) {
            return false;
        }

        $bodyId = $this->connection->lastInsertId();
        $stmt = $this->connection->prepare("INSERT INTO `opos-olas` (OPO_Id_FK, OLA_Id_FK) VALUES (:OPO_Id_FK, :OLA_Id_FK)");
        $stmt->bindParam(':OPO_Id_FK', $opoId, PDO::PARAM_INT);
        $stmt->bindParam(':OLA_Id_FK', $bodyId, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            $this->connection->rollback();
            return false;
        }

        $this->connection->commit();

        return $bodyId;
    }

    public function update($body, $id)
    {
        $stmt = $this->connection->prepare("UPDATE olas SET Code=:Code, Naam=:Naam, Studiepunten=:Studiepunten, IsActief=:IsActief, Jaarduur=:Jaarduur WHERE Id=:Id");
        $stmt->bindParam(':Code', $body['Code'], PDO::PARAM_STR);
        $stmt->bindParam(':Naam', $body['Naam'], PDO::PARAM_STR);
        $stmt->bindParam(':Studiepunten', $body['Studiepunten'], PDO::PARAM_INT);
        $stmt->bindParam(':IsActief', $body['IsActief'], PDO::PARAM_INT);
        $stmt->bindParam(':Jaarduur', $body['Jaarduur'], PDO::PARAM_STR);
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM olas WHERE Id = :Id");
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function addDocent($olaId, $body)
    {
        $this->connection->beginTransaction();

        foreach ($body['DocentenIds'] as &$docentId) {
            $stmt = $this->connection->prepare("INSERT INTO `olas-onderwijspersoneel` (OLA_Id_FK, Docent_Id_FK, Toewijzingsdatum) VALUES (:OLA_Id_FK, :Docent_Id_FK, :Toewijzingsdatum)");
            $stmt->bindParam(':OLA_Id_FK', $olaId, PDO::PARAM_INT);
            $stmt->bindParam(':Docent_Id_FK', $docentId, PDO::PARAM_STR);
            $stmt->bindParam(':Toewijzingsdatum', $body['Toewijzingsdatum'], PDO::PARAM_STR);
            if (!$stmt->execute()) {
                $this->connection->rollback();
                return false;
            }
        }

        $this->connection->commit();
        return true;
    }

    public function removeDocent($olaId, $docentId)
    {
        $stmt = $this->connection->prepare("DELETE FROM `olas-onderwijspersoneel` WHERE Docent_Id_FK = :Docent_Id_FK AND OLA_Id_FK = :OLA_Id_FK");
        $stmt->bindParam(':OLA_Id_FK', $olaId, PDO::PARAM_INT);
        $stmt->bindParam(':Docent_Id_FK', $docentId, PDO::PARAM_STR);
        return $stmt->execute();
    }
}
