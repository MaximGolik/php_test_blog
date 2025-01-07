<?php

namespace App\Services;

use App\Entities\Article;
use App\Entities\User;
use App\Services\DTO\ArticleDTO;
use App\Services\DTO\CreateArticleDTO;
use App\Services\DTO\UpdateArticleDTO;

interface ArticleServiceInterface
{
    public function findArticleById(int $id): Article;
    public function getAllArticles(): array;
    public function createArticle(CreateArticleDTO $dto, User $user): Article;
    public function updateArticle(int $id, UpdateArticleDTO $dto): Article;
    public function deleteArticle(int $id): void;
    public function checkArticleOwner(Article $article): void;
    public function toDTO(Article $article): ArticleDTO;
}

