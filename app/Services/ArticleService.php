<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\Article;
use App\Entities\User;
use App\Exceptions\ArticleAccessDeniedException;
use App\Exceptions\ArticleNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Auth;

class ArticleService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #todo тут применить декоратор, тут же применить dto
    public function findArticleById(int $id)
    {
        $article = $this->entityManager->find(Article::class, $id);

        if (!$article) {
            throw new ArticleNotFoundException();
        }

        return $article;
    }

    #todo добавить везде типизацию аргументов
    public function checkArticleOwner(Article $article): void
    {
        if ($article->getUser()->getId() !== Auth::id()) {
            throw new ArticleAccessDeniedException();
        }
    }

    public function getAllArticles(): array
    {
        #todo не достает в ответ private и protected переменные в entities, разобраться почему
        $repository = $this->entityManager->getRepository(Article::class);
        $articles = $repository->findAll();

        return $articles;
    }

    public function createArticle(array $data, User $user): Article
    {
        $article = new Article();
        $article->setTitle($data['title']);
        $article->setContent($data['content']);
        $article->setUser($user);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $article;
    }

    public function updateArticle(Article $article, array $data): void
    {
        $article->setTitle($data['title']);
        $article->setContent($data['content']);

        $this->entityManager->flush();
    }

    public function deleteArticle(Article $article): void
    {
        $this->entityManager->remove($article);
        $this->entityManager->flush();
    }
}
