<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    // все статьи
    public function index(){
        $articles = Article::all();
        return response()->json(['articles' => $articles]);
    }
    // добавить статью
    public function add(){
        $validatedData = request()->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $article = Article::create([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'user_id' => Auth::id()
        ]);

        return response()->json(['message'=>'Article added','article' => $article]);
    }
    // получить статью
    public function show($id){
        $article = Article::find($id);

        if(!$article){
            return response()->json(['error'=>'Article not found'],404);
        }
        return response()->json(['article' => $article]);
    }
    // изменить статью
    public function update($id){
        // проверяем есть ли статья по её ид
        $article = Article::find($id);
        if(!$article){
            return response()->json(['error'=>'Article not found'],404);
        }

        // менять можно только свою статью, валидация
        if($article->user_id != Auth::id()){
            return response()->json(['error'=>'Not enough rights',404]);
        }

        $validatedData = request()->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $article->update($validatedData);

        return response()->json(['message'=>'Article is updated','article' => $article]);
    }
    // удалить статью
    public function delete($id){
        // проверяем есть ли статья по её ид
        $article = Article::find($id);
        if(!$article){
            return response()->json(['error'=>'Article not found'],404);
        }
        // удалить можно только свою статью, валидация
        if($article->user_id != Auth::id()){
            return response()->json(['error'=>'Not enough rights',404]);
        }
        $article->delete();
        return response()->json(['message'=>'Article deleted successfully']);
    }
}
