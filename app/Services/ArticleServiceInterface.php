<?php

namespace App\Services;

use App\Entities\Article;
use App\Entities\User;
use App\Services\DTO\ArticleDTO;
use App\Services\DTO\TranslateArticleDTO;

interface ArticleServiceInterface
{
    public function findArticleById(int $id): Article;
    public function getAllArticles(): array;
    public function createArticle(TranslateArticleDTO $dto, User $user): Article;
    public function updateArticle(int $id, TranslateArticleDTO $dto): Article;
    public function deleteArticle(int $id): void;
    public function checkArticleOwner(Article $article): void;
    public function toDTO(Article $article): ArticleDTO;
}

