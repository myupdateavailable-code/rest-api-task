<?php

declare(strict_types=1);

namespace App\Controllers\User;

use App\Controllers\Controller;
use App\DTO\Auth\AuthDTO;
use App\DTO\RequestDTO;
use App\Services\User\UserService;

class UserController extends Controller
{

    public function index(UserService $service, RequestDTO $request)
    {
        return $this->jsonResponse(
            $service->getAllUsers($request->get())
        );
    }

    public function show(int $id, UserService $service)
    {
        return $this->jsonResponse(
            $service->getUserById($id)
        );
    }

    public function update(int $id, UserService $service, RequestDTO $request, AuthDTO $auth)
    {
        if ($auth->id() != $id) {
            return  $this->jsonResponse(['message' => 'You are not authorized'], 403);
        }

        $data = !empty($request->json()) ?
            json_decode($request->json(), true) :
            $request->post();

        $userUpdated = $service->updateUser($id, $data);

        if (!$userUpdated) {
            return $this->jsonResponse([
                'message' => 'Unable to update user'
            ], 500);
        }

        return $this->jsonResponse([
            'message' => 'User updated',
        ]);
    }

    public function delete(int $id, UserService $service, AuthDTO $auth)
    {
        if ($auth->id() != $id) {
            return $this->jsonResponse([
                'message' => 'You are not authorized',
            ], 403);
        }
        $userDeleted = $service->deleteUser($id);
        if (!$userDeleted) {
            return $this->jsonResponse([
                'message' => 'Unable to delete user'
            ], 500);
        }

        return $this->jsonResponse([
            'message' => 'User deleted',
        ]);
    }

}
