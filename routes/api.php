<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

#todo подумать куда можно деть singleton и adapter

Route::post('register', static function(RegisterUserRequest $request) {
    $validated = $request->validated();
    return app(RegisterController::class)->create($validated);
});
Route::post('login', [LoginController::class, 'login']);

//todo добавил кастомный фильтр check-token, разобраться как их заставить работать вместе с auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/users/{id}', static function(UpdateUserRequest $request, int $id) {
        $validated = $request->validated();
        return app(UserController::class)->update($validated, $id);
    });
    Route::delete('/users/{id}', [UserController::class, 'delete']);
    Route::get('/users/{id}', [UserController::class, 'get']);

    Route::get('/articles', [ArticleController::class, 'index']);
    Route::post('/articles', static function (StoreArticleRequest $request) {
        $validated = $request->validated();
        return app(ArticleController::class)->add($validated);
    });
    Route::get('/articles/{id}', [ArticleController::class, 'show']);
    Route::put('/articles/{id}', static function (int $id, UpdateArticleRequest $request) {
        $validated = $request->validated();
        return app(ArticleController::class)->update($id, $validated);
    });
    Route::delete('/articles/{id}', [ArticleController::class, 'delete']);
});
