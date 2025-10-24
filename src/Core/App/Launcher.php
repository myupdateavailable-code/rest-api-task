<?php

declare(strict_types=1);

namespace App\Core\App;

use App\Core\Database;
use App\Core\QueryManager\QueryManager;
use App\DTO\Auth\AuthDTO;
use App\DTO\RequestDTO;
use App\Interfaces\DependencyContainerInterface;
use App\Interfaces\RouterInterface;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;

class Launcher
{
    public function initRouter(RouterInterface $router)
    {
        return $router->getRoute($_SERVER['REQUEST_METHOD'], '');
    }

    public function initDependencies(DependencyContainerInterface $c): void
    {
        $c->set(Database::class, new Database());
        $c->set(QueryManager::class, new QueryManager($c->get(Database::class)->getConnection()));
        $c->set(AuthService::class, new AuthService($c->get(QueryManager::class)));
        $c->set(UserService::class, new UserService($c->get(QueryManager::class)));
        $c->set(RequestDTO::class,
            new RequestDTO(
                $_SERVER['REQUEST_METHOD'],
                getallheaders(),
                $_GET,
                $_POST,
                file_get_contents('php://input')
            )
        );
    }

    public function initUser(DependencyContainerInterface $c): bool
    {
        $header = $c->get(RequestDTO::class)->header('Authorization');
        if (null === $header) {
            return false;
        }
        if (null === $header['Authorization']) {
            return false;
        }

        $token = str_replace('Bearer ', '', $header['Authorization']);
        $user = $c->get(AuthService::class)->getUserByToken($token);
        if (null === $user) {
            return false;
        }

        $c->set(AuthDTO::class, AuthDTO::create($user['id'], $user['email']));

        return true;
    }

}