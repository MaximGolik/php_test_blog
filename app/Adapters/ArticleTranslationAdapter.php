<?php

declare(strict_types=1);

namespace App\Adapters;
use App\Entities\Article;
use App\Services\DTO\ArticleDTO;
use App\Services\TranslationService;

class ArticleTranslationAdapter{

    private TranslationService $translationService;

    public function __construct(){
        $this->translationService = TranslationService::getInstance();
    }

    public function translateArticle(Article $article, string $lang = 'ru'): ArticleDTO
    {
        $translatedTitle = $this->translationService->translate($article->getTitle(), $lang);
        $translatedContent = $this->translationService->translate($article->getContent(), $lang);

        return new ArticleDTO(
            $article->getId(),
            $translatedTitle,
            $translatedContent,
            $article->getUser()->getId()
        );
    }

}
