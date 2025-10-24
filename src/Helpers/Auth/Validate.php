<?php

declare(strict_types=1);

namespace App\Helpers\Auth;

/**
 * Helper for auth basic check
 */
class Validate
{
    public static function authCredentials(array $payload): bool
    {
        if (empty($payload)) {
            return false;
        }
        return self::authCredentialsEmail($payload) && self::authCredentialsPassword($payload);
    }

    public static function authCredentialsPassword(array $payload): bool
    {
        if (!isset($payload['password']) || empty($payload['password'])) {
            return false;
        }
        if (strlen($payload['password']) < 3) {
            return false;
        }
        return true;
    }

    public static function authCredentialsEmail(array $payload): bool
    {
        if (!isset($payload['email']) || empty($payload['email'])) {
            return false;
        }
        if (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

}