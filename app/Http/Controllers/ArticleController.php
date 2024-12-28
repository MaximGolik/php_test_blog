<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\ArticleAccessDeniedException;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\UserNotFoundException;
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
        try {
            $user = $this->userService->findUserById(Auth::id());
        } catch (UserNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }

        $article = $this->articleService->createArticle($validatedData, $user);

        return response()->json(['message' => 'Article added', 'article' => $article->getArticleInfo()]);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $article = $this->articleService->findArticleById($id);
            return response()->json(['article' => $article->getArticleInfo()]);
        } catch (ArticleNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function update(int $id, array $validatedData): JsonResponse
    {
        try {
            $article = $this->articleService->findArticleById($id);
        } catch (ArticleNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }

        $this->articleService->updateArticle($article, $validatedData);

        return response()->json(['message' => 'Article is updated', 'article' => $article->getArticleInfo()]);
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $article = $this->articleService->findArticleById($id);
            $this->articleService->checkArticleOwner($article);
        } catch (ArticleNotFoundException|ArticleAccessDeniedException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }

        $this->articleService->deleteArticle($article);

        return response()->json(['message' => 'Article deleted successfully']);
    }
}
