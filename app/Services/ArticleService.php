<?php

declare(strict_types = 1);

namespace App\Services;

use App\Entities\Article;
use App\Exceptions\ArticleAccessDeniedException;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\ArticleValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleService
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function validateArticle($article){
        #todo вынести валидацию из сервисов в шаблоны на уровень роутов
        $validator = Validator::make($article,[
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        if ($validator->fails()) {
            throw new ArticleValidationException($validator->errors(), $article);
        }
        return $article;
    }
    #todo тут применить декоратор, тут же применить dto
    public function findArticleById($id)
    {
        $article = $this->entityManager->find(Article::class, $id);

        if (!$article) {
            throw new ArticleNotFoundException();
        }

        return $article;
    }
    #todo добавить везде типизацию аргументов
    public function checkArticleOwner($article): void
    {
        // Если текущий пользователь не является владельцем статьи, выбрасываем исключение
        if ($article->getUser()->getId() !== Auth::id()) {
            throw new ArticleAccessDeniedException();
        }
    }
    public function getAllArticles(): array
    {
        #todo не достает в ответ private и protected переменные в entities, разобраться почему
        $repository = $this->entityManager->getRepository(Article::class);
        $articles = $repository->findAll();

        return $articles;
    }
}
