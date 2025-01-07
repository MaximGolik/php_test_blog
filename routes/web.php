<?php

use Illuminate\Support\Facades\Route;

#todo сделать фабрику статей, чтобы она имела разделение по топикам
Route::get('/', function () {
    return view('welcome');
});

