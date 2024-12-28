<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\UserNotFoundException;
use App\Services\UserService;
use Illuminate\Http\JsonResponse as JsonResponse;

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function update(array $validatedData, $id): JsonResponse
    {
        try{
            $user = $this->userService->findUserById($id);
            $this->userService->updateUser($validatedData,$user);

            return response()->json(['message' => 'User updated successfully', 'user' => $user->getUserInfo()]);
        }
        catch (UserNotFoundException $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $user = $this->userService->findUserById($id);
            $this->userService->deleteUser($user);
            return response()->json(['message' => 'User deleted successfully']);
        } catch (UserNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function get($id): JsonResponse
    {
        try {
            $user = $this->userService->findUserById($id);
            return response()->json(['user' => $user->getUserInfo()]);
        } catch (UserNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
