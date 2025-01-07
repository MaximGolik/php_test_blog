<?php

namespace App\Services\DTO;

class CreateArticleDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content
    ) {}
}
