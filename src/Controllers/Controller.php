<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Interfaces\ControllerInterface;

/**
 * Main controller. Unifies response.
 */
abstract class Controller implements ControllerInterface
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
