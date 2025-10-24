<?php

declare(strict_types=1);

namespace App\Core\App;

use App\DTO\RequestDTO;
use App\Interfaces\DependencyContainerInterface;

class Guard
{
    public function protectHandler($handler, DependencyContainerInterface $c): ?bool
    {
        if (!$handler['auth']) {
            return false;
        }

        if (null === $c->get(RequestDTO::class)->header('Authorization')) {
            return null;
        }

        return true;
    }
}