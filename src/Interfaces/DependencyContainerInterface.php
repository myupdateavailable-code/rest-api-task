<?php

declare(strict_types=1);

namespace App\Interfaces;

interface DependencyContainerInterface
{

    public function set(string $className, object $object): void;

    public function get($className);

}