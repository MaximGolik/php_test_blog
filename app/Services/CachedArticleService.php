<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\Article;
use App\Entities\User;
use App\Services\DTO\ArticleDTO;
use App\Services\DTO\TranslateArticleDTO;
use Illuminate\Support\Facades\Cache;

class CachedArticleService implements ArticleServiceInterface
{
    private const CACHE_TTL = 3600; // 1 час
    private const CACHE_KEY_PREFIX = 'article:';

    public function __construct(private ArticleService $articleService){}

    public function findArticleById(int $id): Article
    {
        $cacheKey = self::CACHE_KEY_PREFIX . $id;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($id) {
            return $this->articleService->findArticleById($id);
        });
    }

    public function getAllArticles(): array
    {
        return Cache::remember('articles.all', self::CACHE_TTL, function () {
            return $this->articleService->getAllArticles();
        });
    }

    public function createArticle(TranslateArticleDTO $dto, User $user): Article
    {
        $article = $this->articleService->createArticle($dto, $user);
        Cache::forget('articles.all');

        return $article;
    }

    public function updateArticle(int $id, TranslateArticleDTO $dto): Article
    {
        $article = $this->articleService->updateArticle($id, $dto);

        Cache::forget(self::CACHE_KEY_PREFIX . $id);
        Cache::forget('articles.all');

        return $article;
    }

    public function deleteArticle(int $id): void
    {
        $this->articleService->deleteArticle($id);

        Cache::forget(self::CACHE_KEY_PREFIX . $id);
        Cache::forget('articles.all');
    }

    public function checkArticleOwner(Article $article): void
    {
        $this->articleService->checkArticleOwner($article);
    }

    public function toDTO(Article $article): ArticleDTO
    {
        return $this->articleService->toDTO($article);
    }
}
