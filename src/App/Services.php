<?php

declare (strict_types = 1);

use App\Service\OlaService;
use App\Service\OpoService;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;

$container['OpoService'] = static function (Container $c): OpoService {
    return new OpoService(new Psr11Container($c));
};

$container['OlaService'] = static function (Container $c): OlaService {
    return new OlaService(new Psr11Container($c));
};