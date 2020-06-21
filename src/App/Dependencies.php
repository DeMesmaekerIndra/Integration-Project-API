<?php

declare(strict_types=1);

use Pimple\Container;

$container['pdo'] = static function (Container $c): PDO {
    $db = $c['db'];
    $database = sprintf('mysql:host=%s;dbname=%s', $db['hostname'], $db['database']);

    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    );

    try {
        $pdo = new PDO($database, $db['username'], $db['password'], $options);
    } catch (PDOException $e) {
        throw $e;
    }

    return $pdo;
};
