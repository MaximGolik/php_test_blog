<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotFoundException;
use App\Services\UserService;
use Barryvdh\Debugbar\Facades\Debugbar;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Entities\User;

class UserController
{

    private $entityManager;
    private $userService;

    public function __construct(EntityManagerInterface $entityManager, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
    }

    public function update(Request $request, $id)
    {
        try{
            $user = $this->userService->findUserById($id);

            #todo вынести в сервис
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|min:6|string',
            ]);
            $validatedData['password'] = bcrypt($validatedData['password']);

            $user->setName($validatedData['name']);
            $user->setEmail($validatedData['email']);
            $user->setPassword($validatedData['password']);

            $this->entityManager->flush(); // Сохранить изменения в базе данных

            #todo разобраться почему вместо сообщения об успехе, кидает страницу ларавеля
            return response()->json(['message' => 'User updated successfully', 'user' => $user->getUserInfo()]);
        }
        catch (UserNotFoundException $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function delete($id)
    {
        try {
            $user = $this->userService->findUserById($id);

            #todo вынести в сервис
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            return response()->json(['message' => 'User deleted successfully']);
        } catch (UserNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function get($id)
    {
        try {
            $user = $this->userService->findUserById($id);
            return response()->json(['user' => $user->getUserInfo()]);
        } catch (UserNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
