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

require '../vendor/autoload.php';

$router = include __DIR__ . '/../config/routes.php';

$response = new Response();

$launcher = new Launcher();
$handler = $launcher->initRouter($router);

if (null === $handler) {
    $response->error(404);
}

$container = new DependencyContainer();
$launcher->initDependencies($container);

$guard = new Guard();
$protect = $guard->protectHandler($handler, $container);
if (null === $protect) {
    $response->error(401);
}

if ($protect) {
    $user = $launcher->initUser($container);
    if (false === $user || null === $user) {
        $response->error(401);
    }
}

$core = new Core();
$result = $core->handleRequest($handler, $container);

$response->json($result);