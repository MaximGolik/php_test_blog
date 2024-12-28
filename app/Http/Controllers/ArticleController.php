<?php

namespace App\Http\Controllers;


use App\Entities\Article;
use App\Exceptions\ArticleAccessDeniedException;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\ArticleValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Entities\User;
use Illuminate\Support\Facades\Auth;
use App\Services\ArticleService;

#todo вынести всю валидацию и обработку ошибок
class ArticleController
{
    private EntityManagerInterface $entityManager;
    private ArticleService $articleService;

    public function __construct(EntityManagerInterface $entityManager, ArticleService $articleValidationService)
    {
        $this->entityManager = $entityManager;
        $this->articleService = $articleValidationService;
    }

    // все статьи
    public function index()
    {
        $articles = $this->articleService->getAllArticles();
        return response()->json(['articles' => $articles]);
    }

    // добавить статью
    public function add()
    {
        #todo вынести код с пользователем отсюда в сервис, поиск по токену
        $user = $this->entityManager->find(User::class, Auth::id());
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        try {
            $validatedData = $this->articleService->validateArticle(request()->all());
        } catch (ArticleValidationException $e) {
            return response()->json(['errors' => $e->getErrors()->toArray()], 400);
        }

        $article = new Article();
        $article->setTitle($validatedData['title']);
        $article->setContent($validatedData['content']);
        $article->setUser($user);

        #todo вынести в сервис
        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return response()->json(['message' => 'Article added', 'article' => $article->getArticleInfo()]);
    }

    // получить статью
    public function show($id)
    {
        try {
            $article = $this->articleService->findArticleById($id);
            return response()->json(['article' => $article->getArticleInfo()]);
        } catch (ArticleNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    // изменить статью
    public function update($id)
    {
        try {
            $article = $this->articleService->findArticleById($id);
            $this->articleService->checkArticleOwner($article);
            $validatedData = $this->articleService->validateArticle(request()->all());
        } catch (ArticleNotFoundException|ArticleAccessDeniedException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        } catch (ArticleValidationException $e) {
            return response()->json(['errors' => $e->getErrors()->toArray()], 400);
        }

        $article->setTitle($validatedData['title']);
        $article->setContent($validatedData['content']);

        #todo вынести в сервис
        $this->entityManager->flush();

        return response()->json(['message' => 'Article is updated', 'article' => $article->getArticleInfo()]);
    }

    // удалить статью
    public function delete($id)
    {
        try {
            $article = $this->articleService->findArticleById($id);
            $this->articleService->checkArticleOwner($article);

        } catch (ArticleNotFoundException|ArticleAccessDeniedException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }

        #todo вынести в сервис
        $this->entityManager->remove($article);
        $this->entityManager->flush();

        return response()->json(['message' => 'Article deleted successfully']);
    }
}
