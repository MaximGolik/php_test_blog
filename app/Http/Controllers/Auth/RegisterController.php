<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Services\UserService;
use Illuminate\Http\JsonResponse as JsonResponse;

class RegisterController
{
    protected UserService $userService;
    #todo привести конструктор к новому формату (не понятно к какому)
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function create(array $data): JsonResponse
    {
        $user = $this->userService->createUser($data);

        return response()->json([
            'user' => $user->getUserInfo(),
        ], 201);
    }
}
