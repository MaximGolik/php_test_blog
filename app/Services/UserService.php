<?php

namespace App\Services;

use App\Entities\User;
use App\Exceptions\UserAccessTokenException;
use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserDeleteException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findUserById($id): User
    {
        $user = $this->entityManager->find(User::class, $id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    // метод не используется
    public function findAuthenticatedUser(): User
    {
        /*
         * пока бесполезная обработка из-за дефолтного middleware,
         * который выкидывает - Route [login] not defined. c некорректным токеном в запросе
        */
        $userId = Auth::id();
        if (!$userId) {
            throw new UserAccessTokenException();
        }

        $user = $this->entityManager->find(User::class, $userId);
        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function createUser(array $data): User
    {
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword(Hash::make($data['password']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function updateUser(array $data, User $user): void
    {
        $data['password'] = bcrypt($data['password']);

        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

        $this->entityManager->flush();
    }

    public function deleteUser(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
