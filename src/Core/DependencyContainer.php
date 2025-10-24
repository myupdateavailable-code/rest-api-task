<?php

declare(strict_types=1);

namespace App\Core;

use App\Interfaces\DependencyContainerInterface;

class DependencyContainer implements DependencyContainerInterface
{
    private array $instance;

    public function set(string $className, object $object): void
    {
        $this->instance[$className] = $object;
    }

    public function get($className)
    {
        if (!isset($this->instance[$className])) {
            return null;
        }
        return $this->instance[$className];
    }
}