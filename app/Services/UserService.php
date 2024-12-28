<?php

namespace App\Services;

use App\Entities\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserDeleteException;

class UserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findUserById($id)
    {
        $user = $this->entityManager->find(User::class, $id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

}
