<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse as JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Services\ArticleService;

#todo вынести всю обработку ошибок
class ArticleController
{
    private ArticleService $articleService;
    private UserService $userService;

    public function __construct(
        ArticleService $articleService,
        UserService    $userService)
    {
        $this->articleService = $articleService;
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        $articles = $this->articleService->getAllArticles();
        return response()->json(['articles' => $articles]);
    }

    public function add(array $validatedData): JsonResponse
    {
        $user = $this->userService->findUserById(Auth::id());
        $article = $this->articleService->createArticle($validatedData, $user);

        return response()->json(['message' => 'Article added', 'article' => $article->getArticleInfo()]);
    }

    public function show(int $id): JsonResponse
    {
        $article = $this->articleService->findArticleById($id);
        return response()->json(['article' => $article->getArticleInfo()]);
    }

    public function update(int $id, array $validatedData): JsonResponse
    {
        $article = $this->articleService->findArticleById($id);
        $this->articleService->updateArticle($article, $validatedData);

        return response()->json(['message' => 'Article is updated', 'article' => $article->getArticleInfo()]);
    }

    public function delete(int $id): JsonResponse
    {
        $article = $this->articleService->findArticleById($id);
        $this->articleService->checkArticleOwner($article);
        $this->articleService->deleteArticle($article);

        return response()->json(['message' => 'Article deleted successfully']);
    }
}
