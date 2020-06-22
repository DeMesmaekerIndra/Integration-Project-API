<?php

declare(strict_types=1);

use App\Repository\OlaRepository;
use App\Repository\OpoRepository;
use App\Repository\PersoneelRepository;
use App\Repository\StudentRepository;
use App\Repository\InschrijvingsRepository;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;

$container['OpoRepository'] = static function (Container $c): OpoRepository {
    return new OpoRepository(new Psr11Container($c));
};

$container['OlaRepository'] = static function (Container $c): OlaRepository {
    return new OlaRepository(new Psr11Container($c));
};

$container['PersoneelRepository'] = static function (Container $c): PersoneelRepository {
    return new PersoneelRepository(new Psr11Container($c));
};

$container['StudentRepository'] = static function (Container $c): StudentRepository {
    return new StudentRepository(new Psr11Container($c));
};

$container['InschrijvingsRepository'] = static function (Container $c): InschrijvingsRepository {
    return new InschrijvingsRepository(new Psr11Container($c));
};
