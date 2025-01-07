<?php

namespace App\Services\DTO;

class UpdateArticleDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content
    ) {}
}
