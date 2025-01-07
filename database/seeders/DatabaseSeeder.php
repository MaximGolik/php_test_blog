<?php

namespace Database\Seeders;

use Database\Factories\ArticleFactory;
use Database\Factories\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = UserFactory::create();
            $this->entityManager->persist($user);

            $article = ArticleFactory::create(['user' => $user]);
            $this->entityManager->persist($article);
        }
        $this->entityManager->flush();

        echo "10 статей и пользователей успешно созданы!" . PHP_EOL;
    }
}
