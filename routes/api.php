<?php

use App\Http\Controllers\UserController;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ArticleController;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

#todo подумать куда можно деть singleton и adapter

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

#todo сюда вынести валидацию
//todo добавил кастомный фильтр check-token, разобраться как их заставить работать вместе с auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    // crud пользователей
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'delete']);
    Route::get('/users/{id}', [UserController::class, 'get']);

    // crud для статей
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::post('/articles', [ArticleController::class, 'add']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);
    Route::put('/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'delete']);
});
