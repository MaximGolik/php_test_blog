<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\Article;
use App\Entities\User;
use App\Exceptions\ArticleAccessDeniedException;
use App\Exceptions\ArticleNotFoundException;
use App\Services\DTO\ArticleDTO;
use App\Services\DTO\CreateArticleDTO;
use App\Services\DTO\UpdateArticleDTO;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Auth;

class ArticleService implements ArticleServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function findArticleById(int $id): Article
    {
        $article = $this->entityManager->find(Article::class, $id);

        if (!$article) {
            throw new ArticleNotFoundException();
        }

        return $article;
    }

    public function getAllArticles(): array
    {
        $repository = $this->entityManager->getRepository(Article::class);
        return $repository->findAll();
    }

    public function createArticle(CreateArticleDTO $dto, User $user): Article
    {
        $article = new Article();
        $article->setTitle($dto->title);
        $article->setContent($dto->content);
        $article->setUser($user);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $article;
    }

    public function updateArticle(int $id, UpdateArticleDTO $dto): Article
    {
        $article = $this->findArticleById($id);

        $article->setTitle($dto->title);
        $article->setContent($dto->content);

        $this->entityManager->flush();

        return $article;
    }

    public function deleteArticle(int $id): void
    {
        $article = $this->findArticleById($id);
        $this->entityManager->remove($article);
        $this->entityManager->flush();
    }

    public function checkArticleOwner(Article $article): void
    {
        if ($article->getUser()->getId() !== Auth::id()) {
            throw new ArticleAccessDeniedException();
        }
    }

    public function toDTO(Article $article): ArticleDTO
    {
        return new ArticleDTO(
            id: $article->getId(),
            title: $article->getTitle(),
            content: $article->getContent(),
            userId: $article->getUser()->getId(),
        );
    }
}
