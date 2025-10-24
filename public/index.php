<?php

declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\Core\App\Core;
use App\Core\App\Guard;
use App\Core\App\Launcher;
use App\Core\DependencyContainer;
use App\Core\Http\Response;

require __DIR__ . '/../vendor/autoload.php';

$router = include __DIR__ . '/../config/routes.php';

// response handler
$response = new Response();

// launcher
$launcher = new Launcher();

// looking for handler
$handler = $launcher->initRouter($router);

if (null === $handler) {
    $response->error(404);
}

// Registering Dependency Container
$container = new DependencyContainer();
//Filling the container
$launcher->initDependencies($container);

// Guard will check if auth required and if Authorization header exists
$guard = new Guard();
$protect = $guard->protectHandler($handler, $container);

if (null === $protect) {
    $response->error(401);
}

// if auth required we will initiate user
if ($protect) {
    $user = $launcher->initUser($container);
    if (false === $user || null === $user) {
        $response->error(401);
    }
}

// Core will process handler and call needed controller and inject dependencies
$core = new Core();
$result = $core->handleRequest($handler, $container);

// returns handler result in json
$response->json($result);