<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\User;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findUserById(int $id): User
    {
        $user = $this->entityManager->find(User::class, $id);

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
