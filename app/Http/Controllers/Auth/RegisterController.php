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

    protected $entityManager;
    // Конструктор контроллера для внедрения зависимости EntityManager
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function register(Request $request){
        // валидация пароля и имени пользователя
        $validation = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password'=>'required|min:6|string',
        ]);
        if($validation->fails()){
            return response()->json(['errors'=>$validation->errors()], 400);
        }

        // создаем пользователя
        $user = new User();
        $user->setName($request->input('name'));
        $user->setEmail($request->input('email'));
        $user->setPassword(Hash::make($request->input('password')));

        // сохраняем пользователя в базу данных
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // генерация токена для авторизации пользователя
        //  $token = $user->createToken('MyApp')->plainTextToken;


        // json(['user' => $user]) не катит, т.к. теперь там все cвойства приватные
        return response()->json([
            'user'=>$user->getUserInfo(),
//            'token' => $token
        ], 201);
    }
}
