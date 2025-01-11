<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Services\UserService;
use Illuminate\Http\JsonResponse as JsonResponse;

class RegisterController
{
    #todo привести конструктор к новому формату (как тут все остальные сделать)
    public function __construct(protected UserService $userService)
    {
    }

    public function create(array $data): JsonResponse
    {
        $user = $this->userService->createUser($data);

        return response()->json([
            'user' => $user->getUserInfo(),
        ], 201);
    }
}
