<?php

namespace App\Repository;

use PDO;
use Psr\Container\ContainerInterface;

final class PersoneelRepository
{
    private $connection;

    public function __construct(ContainerInterface $container)
    {
        $this->connection = $container->get('pdo');
    }

    public function get($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM onderwijs_personeel WHERE Id = :Id");
        $stmt->bindParam(':Id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM onderwijs_personeel");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($body)
    {
        $stmt = $this->connection->prepare("INSERT INTO onderwijs_personeel (Voornaam, Achternaam, Email , GSM) VALUES (:Voornaam, :Achternaam, :Email, :GSM)");
        $stmt->bindParam(':Voornaam', $body['Voornaam'], PDO::PARAM_STR);
        $stmt->bindParam(':Achternaam', $body['Achternaam'], PDO::PARAM_STR);
        $stmt->bindParam(':Email', $body['Email'], PDO::PARAM_STR);
        $stmt->bindParam(':GSM', $body['GSM'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return false;
        }

        return $this->connection->lastInsertId();
    }

    public function update($id, $body)
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
        $stmt = $this->connection->prepare("DELETE FROM onderwijs_personeel WHERE Id = :Id");
        $stmt->bindParam(':Id', $id, PDO::PARAM_STR);
        return $stmt->execute();
    }
}
