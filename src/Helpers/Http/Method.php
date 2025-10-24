<?php

declare(strict_types=1);

namespace App\Helpers\Http;

/**
 * Helper for Router
 */

enum Method: string
{
    case get = 'GET';

    case post = 'POST';

    case put = 'PUT';

    case delete = 'DELETE';

}