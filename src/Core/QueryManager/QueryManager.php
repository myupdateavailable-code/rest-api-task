<?php

declare(strict_types=1);

namespace App\Core\QueryManager;

final class QueryManager
{

    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Insert and return last inserted ID
    public function insert(string $query, array $params = []): ?int
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        if ((int)$stmt->errorInfo()[0] === 00000) {
            return (int)$this->pdo->lastInsertId();
        }

        return null;
    }

    // Read
    public function select(string $query, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($query);
        if (!$stmt) {
            return null;
        }
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    // Update or Delete
    public function execute(string $query, array $params = []): ?bool
    {
        if (empty($params)) {
            return false;
        }
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute($params);
    }

}
