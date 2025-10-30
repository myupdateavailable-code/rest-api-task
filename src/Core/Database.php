<?php

declare(strict_types=1);

namespace App\Core;

use App\Interfaces\DatabaseConnectionInterface;

class Database implements DatabaseConnectionInterface
{
    private \PDO $connection;

    /**
     * Class constructor.
     */
    public function __construct(array $config = null)
    {
        if (null === $config) {
            $config = include __DIR__ . '/../../config/database.php';
        }

        $this->connection = new \PDO(
            "{$config['driver']}:host={$config['host']};dbname={$config['db_name']};charset={$config['charset']}",
            $config['user'],
            $config['pass'],
            $config['options']
        );

        if ($this->connection->errorCode() !== '00000') {
            throw new \Exception(
                "Connection failed: " . implode(' ', $this->connection->errorInfo())
            );
        }
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }

}