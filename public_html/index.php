<?php

declare (strict_types = 1);

mb_language('uni');
mb_internal_encoding('UTF-8');
require __DIR__ . '/../src/App/App.php';
$app = new App();
$app = $app->getApp();
$app->run();
