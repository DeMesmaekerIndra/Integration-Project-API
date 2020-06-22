<?php

declare(strict_types=1);

use App\Service\OlaService;
use App\Service\OpoService;
use App\Service\PersoneelService;
use App\Service\StudentService;
use App\Service\InschrijvingsService;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;

$container['OpoService'] = static function (Container $c): OpoService {
    return new OpoService(new Psr11Container($c));
};

$container['OlaService'] = static function (Container $c): OlaService {
    return new OlaService(new Psr11Container($c));
};

$container['PersoneelService'] = static function (Container $c): PersoneelService {
    return new PersoneelService(new Psr11Container($c));
};

$container['StudentService'] = static function (Container $c): StudentService {
    return new StudentService(new Psr11Container($c));
};

$container['InschrijvingsService'] = static function (Container $c): InschrijvingsService {
    return new InschrijvingsService(new Psr11Container($c));
};
