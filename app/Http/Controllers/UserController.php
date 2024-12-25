<?php

namespace App\Http\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Entities\User;
class UserController {

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function update(Request $request, $id) {
        $user = $this->entityManager->find(User::class, $id);

        if(!$user) {
            return response()->json([
                'message' => 'User not found'
            ],400);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password'=>'required|min:6|string',
        ]);
        $validatedData['password'] = bcrypt($validatedData['password']);

        $user->setName($validatedData['name']);
        $user->setEmail($validatedData['email']);
        $user->setPassword($validatedData['password']);

        $this->entityManager->flush(); // Сохранить изменения в базе данных

        return response()->json([ 'message' => 'User updated successfully', 'user'=>$user->getUserInfo()]);
    }
    public function delete($id) {
        $user = $this->entityManager->find(User::class, $id);

        if(!$user) {
            return response()->json([
                'message' => 'User not found'
            ],400);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function get($id) {
        $user = $this->entityManager->find(User::class, $id);
        if(!$user) {
            return response()->json(['message' => 'User not found'], 400);
        }
        return response()->json(['user'=>$user->getUserInfo()]);
    }
}
