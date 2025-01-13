<?php

declare(strict_types=1);

namespace App\Translators;

use App\Services\DTO\TranslateArticleDTO;

class ArticleRequestTranslator
{
    public function translate(array $validatedData): TranslateArticleDTO
    {
        return new TranslateArticleDTO(
            title: $validatedData['title'],
            content: $validatedData['content']
        );
    }
}
