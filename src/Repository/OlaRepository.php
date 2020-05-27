<?php
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
        $stmt = $this->connection->prepare("SELECT * FROM OLAs WHERE Id = :Id");
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getByOpo($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM OLAs WHERE Id IN (SELECT OLA_Id_FK FROM `OPOs-OLAs` WHERE OPO_Id_FK = :Id)");
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM OLAs");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($newOla, $opoId)
    {
        $this->connection->beginTransaction();
        $stmt = $this->connection->prepare("INSERT INTO OLAs (Code, Naam, Studiepunten, IsActief, Jaarduur) VALUES (:Code, :Naam, :Studiepunten, :IsActief, :Jaarduur)");
        $stmt->bindParam(':Code', $newOla['Code'], PDO::PARAM_STR);
        $stmt->bindParam(':Naam', $newOla['Naam'], PDO::PARAM_STR);
        $stmt->bindParam(':Studiepunten', $newOla['Studiepunten'], PDO::PARAM_INT);
        $stmt->bindParam(':IsActief', $newOla['IsActief'], PDO::PARAM_BOOL);
        $stmt->bindParam(':Jaarduur', $newOla['Jaarduur'], PDO::PARAM_STR);

        if (!$stmt->execute()) {
            return false;
        }

        $newOlaId = $this->connection->lastInsertId();
        $stmt = $this->connection->prepare("INSERT INTO `OPOs-OLAs` (OPO_Id_FK, OLA_Id_FK) VALUES (:OPO_Id_FK, :OLA_Id_FK)");
        $stmt->bindParam(':OPO_Id_FK', $opoId, PDO::PARAM_INT);
        $stmt->bindParam(':OLA_Id_FK', $newOlaId, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            $this->connection->rollback();
            return false;
        }

        $this->connection->commit();

        return $newOlaId;
    }
}
