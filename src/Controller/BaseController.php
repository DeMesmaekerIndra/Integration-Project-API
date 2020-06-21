<?php

declare(strict_types=1);

namespace App\Controller;

abstract class BaseController
{
    public function __construct()
    {
    }

    protected function findQsParamValue($params, $requiredParam)
    {
        if (!$params || !array_key_exists($requiredParam, $params)) {
            return false;
        }

        return $params[$requiredParam];
    }
}
