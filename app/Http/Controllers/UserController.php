<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse as JsonResponse;

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function update(array $validatedData, int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);
        $this->userService->updateUser($validatedData, $user);

        return response()->json(['message' => 'User updated successfully', 'user' => $user->getUserInfo()]);
    }

    public function delete(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);
        $this->userService->deleteUser($user);

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function get(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        return response()->json(['user' => $user->getUserInfo()]);
    }
}
