<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Core\QueryManager\QueryManager;
use App\Helpers\Auth\Validate;

class AuthService
{

    private QueryManager $queryManager;

    public function __construct(QueryManager $queryManager)
    {
        $this->queryManager = $queryManager;
    }

    public function login(array $credentials): ?string
    {
        // Selecting user
        $user = $this->queryManager->select(
            'SELECT id, email, password FROM `users` WHERE `email` = :email',
            ['email' => $credentials['email']]
        );

        // return null if empty or wrong credentials
        if (!Validate::authCredentials($credentials)) {
            return null;
        }

        // Return null if user not found or credentials wrong
        if (
            empty($user) ||
            !isset($user[0]) ||
            !isset($user[0]['password']) ||
            !password_verify($credentials['password'], $user[0]['password'])
        ) {
            return null;
        }

        // clear old sessions
        $this->queryManager->execute(
            'DELETE FROM `users_token` WHERE `user_id` = :user_id',
            ['user_id' => $user[0]['id']]
        );

        // Let's generate token for our user
        $token = $this->generateToken();

        // Registering session token in DB
        $this->queryManager->insert(
            'INSERT INTO `users_token` (user_id, token) VALUES (:user_id, :token)',
            [
                'user_id' => $user[0]['id'],
                'token' => $token,
            ]
        );

        // Returning token to user
        return $token;
    }

    public function register(array $credentials): ?int
    {
        // Create new scratch file from selection
        if (!Validate::authCredentials($credentials)) {
            return null;
        }

        // prepare password
        $credentials['password'] = password_hash($credentials['password'], PASSWORD_DEFAULT);

        // registering user
        return $this->queryManager->insert(
            'INSERT INTO `users` (email, password) VALUES (:email, :password)',
            [
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ]
        );
    }

    public function logout(string $token = null): ?bool
    {
        if (null === $token) {
            return null;
        }

        // fetching token. Can be added to AuthDTO as an option (AuthDTO modify required)
        $token = str_replace('Bearer ', '', $token);

        return $this->queryManager->execute(
            'DELETE FROM `users_token` WHERE `token` = :token',
            ['token' => $token]
        );
    }

    public function getUserByToken(string $token): ?array
    {
        $user = $this->queryManager->select(
            'SELECT users.id, users.email, users_token.token 
                    FROM `users_token` 
                    JOIN `users` ON users_token.user_id = users.id 
                    WHERE users_token.token = :token',
            ['token' => $token]
        );

        return (!empty($user)) ? $user[0] : null;
    }

    private function generateToken(): string
    {
        // generating random string as token
        return bin2hex(random_bytes(16) . time());
    }

}