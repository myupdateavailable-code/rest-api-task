<?php

declare(strict_types=1);

namespace App\Controllers;

class Welcome extends Controller
{

    public function index()
    {
        return $this->jsonResponse(['status' => 'ok']);
    }

}
