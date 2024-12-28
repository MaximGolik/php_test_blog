<?php

namespace App\Http\Controllers\Auth;

use App\Entities\User;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController
{
    // метод для регистрации пользователя

    protected EntityManagerInterface $entityManager;

    // Конструктор контроллера для внедрения зависимости EntityManager
    #todo привести конструктор к новому формату
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function register(Request $request)
    {
        // валидация пароля и имени пользователя
        #todo вынести в сервис
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:6|string',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 400);
        }

        // создаем пользователя
        $user = new User();
        $user->setName($request->input('name'));
        $user->setEmail($request->input('email'));
        $user->setPassword(Hash::make($request->input('password')));

        #todo вынести в сервис
        // сохраняем пользователя в базу данных
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return response()->json([
            'user' => $user->getUserInfo(),
        ], 201);
    }
}
