<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\WelcomeController;
use App\Core\Router;
use App\Helpers\Http\Method;

$router = new Router();

$router->registerRoute(Method::get, '/', [WelcomeController::class, 'index']);

$router->registerRoute(Method::get, '/users', [UserController::class, 'index'])->auth();
$router->registerRoute(Method::get, '/users/:id', [UserController::class, 'show'])->auth();
$router->registerRoute(Method::put, '/users/:id', [UserController::class, 'update'])->auth();
$router->registerRoute(Method::delete, '/users/:id', [UserController::class, 'delete'])->auth();

$router->registerRoute(Method::post, '/register', [AuthController::class, 'register']);
$router->registerRoute(Method::post, '/login', [AuthController::class, 'login']);
$router->registerRoute(Method::post, '/logout', [AuthController::class, 'logout'])->auth();

return $router;
