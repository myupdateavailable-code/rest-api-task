<?php

declare(strict_types=1);

namespace App\Interfaces;

interface DatabaseConnectionInterface
{
    public function getConnection(): \PDO;
}