<?php

declare(strict_types=1);


namespace App\Services\DTO;

class TranslateArticleDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content
    ) {
    }
}
