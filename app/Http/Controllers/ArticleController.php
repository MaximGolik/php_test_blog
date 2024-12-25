<?php

namespace App\Http\Controllers;


use App\Entities\Article;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Entities\User;
use Illuminate\Support\Facades\Auth;

//todo добить до конца в контроллеарх (логин, пользователь, статьи) все запросы на doctrine orm
class ArticleController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    // все статьи
    public function index(){
        //todo не достает в ответ private и protected переменные в entities, разобраться почему
        $repository = $this->entityManager->getRepository(Article::class);
        $articles = $repository->findAll();

        return response()->json(['articles' => $articles]);

    }
    // добавить статью
    public function add(){
        $user = $this->entityManager->find(User::class, Auth::id());
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validatedData = request()->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $article = new Article();
        $article->setTitle($validatedData['title']);
        $article->setContent($validatedData['content']);
        $article->setUser($user);

        // сохраняем в бд, persist - отслеживает обьект, flush - синхронизирует текущие состояние объекта с БД
        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return response()->json(['message'=>'Article added','article' => $article->getArticleInfo()]);
    }
    // получить статью
    public function show($id){
        $article = $this->entityManager->find(Article::class, $id);

        if(!$article){
            return response()->json(['error'=>'Article not found'],404);
        }
        return response()->json(['article' => $article->getArticleInfo()]);
    }
    // изменить статью
    public function update($id){
        // проверяем есть ли статья по её ид
        $article = $this->entityManager->find(Article::class, $id);
        if(!$article){
            return response()->json(['error'=>'Article not found'],404);
        }

        // менять можно только свою статью, валидация
        if ($article->getUser()->getId() !== Auth::id()) {
            return response()->json(['error' => 'Not enough rights'], 403);
        }

        $validatedData = request()->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $article->setTitle($validatedData['title']);
        $article->setContent($validatedData['content']);

        $this->entityManager->flush();

        return response()->json(['message'=>'Article is updated','article' => $article->getArticleInfo()]);
    }
    // удалить статью
    public function delete($id){
        // проверяем есть ли статья по её ид
        $article = $this->entityManager->find(Article::class, $id);
        if(!$article){
            return response()->json(['error'=>'Article not found'],404);
        }
        // удалить можно только свою статью, валидация
        if ($article->getUser()->getId() !== Auth::id()) {
            return response()->json(['error' => 'Not enough rights'], 403);
        }

        // remove - соответственно удаляет из бд запись
        $this->entityManager->remove($article);
        $this->entityManager->flush();

        return response()->json(['message'=>'Article deleted successfully']);
    }
}
