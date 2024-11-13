<?php

use App\Http\Resources\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::apiResource('/users', User::class);
