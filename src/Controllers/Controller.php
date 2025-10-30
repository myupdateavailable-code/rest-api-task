<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Main controller. Unifies response.
 */
abstract class Controller
{
    public function jsonResponse(array $message, int $statusCode = 200, array $headers = null): array
    {
        return [
            'payload' => $message,
            'code' => $statusCode,
            'headers' => $headers
        ];
    }
}
