<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Core\Http\Request;

interface FromRequestDTO
{
    public static function fromRequest(Request $request): static;
}