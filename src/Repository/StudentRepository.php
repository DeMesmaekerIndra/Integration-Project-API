<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;
use Psr\Container\ContainerInterface;

final class StudentRepository
{
    private $connection;

    public function __construct(ContainerInterface $container)
    {
        $this->connection = $container->get('pdo');
    }

    public function get($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM studenten WHERE Student_NR = :Student_NR");
        $stmt->bindParam(':Student_NR', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM studenten");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($body)
    {
        $stmt = $this->connection->prepare("INSERT INTO studenten (Student_NR, Voornaam, Achternaam, Email , GSM, `Contract`, Traject, Afstudeerbaar, Soort, Inschrijvingsjaar) VALUES (:Student_NR,:Voornaam, :Achternaam, :Email, :GSM, , :Contract, :Traject, :Afstudeerbaar, :Soort, :Inschrijvingsjaar)");
        $stmt->bindParam(':Student_NR', $body['Student_NR'], PDO::PARAM_STR);
        $stmt->bindParam(':Voornaam', $body['Voornaam'], PDO::PARAM_STR);
        $stmt->bindParam(':Achternaam', $body['Achternaam'], PDO::PARAM_STR);
        $stmt->bindParam(':Email', $body['Email'], PDO::PARAM_STR);
        $stmt->bindParam(':GSM', $body['GSM'], PDO::PARAM_STR);
        $stmt->bindParam(':Contract', $body['Contract'], PDO::PARAM_STR);
        $stmt->bindParam(':Traject', $body['Traject'], PDO::PARAM_STR);
        $stmt->bindParam(':Afstudeerbaar', $body['Afstudeerbaar'], PDO::PARAM_INT);
        $stmt->bindParam(':Soort', $body['Soort'], PDO::PARAM_STR);
        $stmt->bindParam(':Inschrijvingsjaar', $body['Inschrijvingsjaar'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function update($id, $body)
    {
        $stmt = $this->connection->prepare("UPDATE studenten SET Voornaam=:Voornaam, Achternaam=:Achternaam, Email=:Email, GSM=:GSM, `Contract`=:Contract:, Traject=:Traject, Afstudeerbaar=:Afstudeerbaar, Soort=:Soort, Inschrijvingsjaar=:Inschrijvingsjaar WHERE Student_NR=:Student_NR");
        $stmt->bindParam(':Student_NR', $id, PDO::PARAM_STR);
        $stmt->bindParam(':Voornaam', $body['Voornaam'], PDO::PARAM_STR);
        $stmt->bindParam(':Achternaam', $body['Achternaam'], PDO::PARAM_STR);
        $stmt->bindParam(':Email', $body['Email'], PDO::PARAM_STR);
        $stmt->bindParam(':GSM', $body['GSM'], PDO::PARAM_STR);
        $stmt->bindParam(':Contract', $body['Contract'], PDO::PARAM_STR);
        $stmt->bindParam(':Traject', $body['Traject'], PDO::PARAM_STR);
        $stmt->bindParam(':Afstudeerbaar', $body['Afstudeerbaar'], PDO::PARAM_INT);
        $stmt->bindParam(':Soort', $body['Soort'], PDO::PARAM_STR);
        $stmt->bindParam(':Inschrijvingsjaar', $body['Inschrijvingsjaar'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM studenten WHERE Student_NR = :Student_NR");
        $stmt->bindParam(':Student_NR', $id, PDO::PARAM_STR);
        return $stmt->execute();
    }
}
