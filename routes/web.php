<?php

use Illuminate\Support\Facades\Route;

#todo сделать фабрику статей, чтобы она имела разделение по топикам
#todo применить паттерн декоратора (например на уровне кеширования статей из БД)
Route::get('/', function () {
    return view('welcome');
});

