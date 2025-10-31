<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DTO\RequestDTO;
use App\Helpers\Auth\Validate;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function login(AuthService $auth, RequestDTO $request)
    {
        $data = json_decode($request->json(), true);
        if (empty($data)) return $this->jsonResponse([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ]);

        $email = $data['email'] ?? "";
        $password = $data['password'] ?? "";

        // login user and return token
        $token = $auth->login($email, $password);

        if (!$token) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 403);
        }

        return $this->jsonResponse([
                'status' => 'success',
                'token' => $token,
            ]);
    }

    public function register(AuthService $auth, RequestDTO $request)
    {
        $data = json_decode($request->json(), true);
        if (empty($data)) return $this->jsonResponse([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ]);

        $email = $data['email'] ?? "";
        $password = $data['password'] ?? "";

        if (!Validate::authCredentialsEmail($email) || !Validate::authCredentialsPassword($password)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 403);
        }

        $credentials = array_merge($email, $password);

        // register user
        $userCreated = $auth->register($credentials);

        if (null === $userCreated) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'User could not be created'
            ], 403);
        }

        return $this->jsonResponse([
            'status' => 'success',
            'message' => ['user_id' => $userCreated]
        ], 201);
    }

    public function logout(AuthService $auth, RequestDTO $request)
    {
        // separating needed header
        $header = $request->header('Authorization');
        $logout = $auth->logout($header['Authorization']);

        // return error if unable to logout existing token
        if (!$logout) {
            return $this->jsonResponse([
                'status' => 'error',
            ], 403);
        }

        return $this->jsonResponse([
            'status' => 'success'
        ]);
    }

}
