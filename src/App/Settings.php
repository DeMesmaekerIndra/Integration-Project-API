<?php

declare(strict_types=1);

return [
    'db' => [
        'hostname' => $_SERVER['DB_HOST'],
        'database' => $_SERVER['DB_NAME'],
        'username' => $_SERVER['DB_USER'],
        'password' => $_SERVER['DB_PASS'],
    ],
];
