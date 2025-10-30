<?php

declare(strict_types=1);

namespace App\Controllers;

class WelcomeController extends Controller
{

    public function index()
    {
        return $this->jsonResponse(['status' => 'ok']);
    }

}
