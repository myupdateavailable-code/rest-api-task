<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\QueryManager\QueryManager;
use App\Helpers\Auth\Validate;

class UserService
{

    private QueryManager $queryManager;

    public function __construct(QueryManager $queryManager)
    {
        $this->queryManager = $queryManager;
    }

    public function getAllUsers(array $search = null): ?array
    {
        // preparing query
        $query = 'SELECT * FROM users';

        if (!empty($search)) {
            $parts = [];
            $query .= ' WHERE ';

            foreach ($search as $key => &$value) {
                $parts[] = $key . ' LIKE :' . $key;
                $value = '%' . $value . '%';
            }
            $query .= implode(' AND ', $parts);
        }

        // fetching users
        return $this->queryManager->select($query, $search);
    }

    public function getUserById(int $userId): ?array
    {
        return $this->queryManager->select(
            'SELECT * FROM users WHERE id = :id',
            ['id' => $userId]
        );
    }

    public function updateUser(int $userId, array $data): bool
    {
        // filter only relevant keys
        foreach ($data as $key => $value) {
            if (!in_array($key, ['email', 'password', 'address', 'age'])) {
                unset($data[$key]);
            }
        }

        if (empty($data)) {
            return false;
        }

        // validating and preparing password if exists
        if (array_key_exists('password', $data)) {
            if (false === Validate::authCredentialsPassword($data)) {
                return false;
            }
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // validating email
        if (array_key_exists('email', $data)) {
            if (false === Validate::authCredentialsEmail($data)) {
                return false;
            }
        }

        // preparing placeholders for query
        $mappedUserData = array_map(function ($keys) {
            return "$keys = :$keys";
        }, array_keys($data));

        $query = "UPDATE users SET ";
        $query .= implode(' , ', $mappedUserData);
        $query .= " WHERE id = :id";

        return $this->queryManager->execute(
            $query,
            array_merge($data, ['id' => $userId])
        );
    }

    public function deleteUser(int $userId): bool
    {
        return $this->queryManager->execute(
            'DELETE FROM users WHERE id = :id',
            ['id' => $userId]
        );
    }

}