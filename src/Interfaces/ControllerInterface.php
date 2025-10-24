<?php

declare(strict_types=1);

namespace App\Interfaces;

interface ControllerInterface
{
    public function jsonResponse(array $message, int $statusCode = 200): array;
}