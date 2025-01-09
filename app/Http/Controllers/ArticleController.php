<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Adapters\ArticleTranslationAdapter;
use App\Services\ArticleServiceInterface;
use App\Services\DTO\CreateArticleDTO;
use App\Services\DTO\UpdateArticleDTO;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController
{
    public function __construct(
        private readonly ArticleServiceInterface   $articleService,
        private readonly UserService               $userService,
        private readonly ArticleTranslationAdapter $articleTranslationAdapter
    )
    {
    }

    public function index(): JsonResponse
    {
        $articles = $this->articleService->getAllArticles();
        $articleDTOs = array_map(
            fn($article) => $this->articleService->toDTO($article),
            $articles
        );
        return response()->json(['articles' => $articleDTOs]);
    }

    //todo создается dto в контроллере, убрать в отдельный класс, спросить надо ли
    public function add(array $validatedData): JsonResponse
    {
        $createDTO = new CreateArticleDTO(
            title: $validatedData['title'],
            content: $validatedData['content']
        );

        $user = $this->userService->findUserById(Auth::id());
        $article = $this->articleService->createArticle($createDTO, $user);
        $articleDTO = $this->articleService->toDTO($article);

        return response()->json([
            'message' => 'Article added',
            'article' => $articleDTO
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $article = $this->articleService->findArticleById($id);
        $articleDTO = $this->articleService->toDTO($article);
        return response()->json(['article' => $articleDTO]);
    }

    //todo создается dto в контроллере, убрать в отдельный класс
    public function update(int $id, array $validatedData): JsonResponse
    {
        $updateDTO = new UpdateArticleDTO(
            title: $validatedData['title'],
            content: $validatedData['content']
        );

        $article = $this->articleService->updateArticle($id, $updateDTO);
        $articleDTO = $this->articleService->toDTO($article);

        return response()->json([
            'message' => 'Article is updated',
            'article' => $articleDTO
        ]);
    }

    public function delete(int $id): JsonResponse
    {
        $article = $this->articleService->findArticleById($id);
        $this->articleService->checkArticleOwner($article);
        $this->articleService->deleteArticle($id);

        return response()->json(['message' => 'Article deleted successfully']);
    }

    public function translate(int $id, Request $request): JsonResponse
    {
        $article = $this->articleService->findArticleById($id);
        $lang = $request->get('lang', 'ru');
        $translatedArticleDTO = $this->articleTranslationAdapter->translateArticle($article, $lang);

        return response()->json([
            'message' => 'Article is translated',
            'article' => $translatedArticleDTO
        ]);
    }
}
