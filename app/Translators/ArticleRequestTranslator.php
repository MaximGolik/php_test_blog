<?php

declare(strict_types=1);

namespace App\Translators;


use App\Services\DTO\CreateArticleDTO;
use App\Services\DTO\UpdateArticleDTO;

class ArticleRequestTranslator
{
    public function toCreateArticleDTO(array $validatedData): CreateArticleDTO
    {
        return new CreateArticleDTO(
            title: $validatedData['title'],
            content: $validatedData['content']
        );
    }

    public function toUpdateArticleDTO(array $validatedData): UpdateArticleDTO
    {
        return new UpdateArticleDTO(
            title: $validatedData['title'],
            content: $validatedData['content']
        );
    }
}
