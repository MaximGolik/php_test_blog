<?php

declare(strict_types=1);

namespace App\Services\DTO;

use App\Entities\Article;

class ArticleDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $content,
        public readonly int $userId,
    ) {}

    public static function fromEntity(Article $article): self
    {
        return new self(
            $article->getId(),
            $article->getTitle(),
            $article->getContent(),
            $article->getUser()->getId(),
        );
    }
}
