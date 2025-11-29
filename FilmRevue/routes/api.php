<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('films', FilmController::class);
Route::apiResource('actors', ActorController::class);
Route::apiResource('languages', LanguageController::class);
Route::apiResource('critics', CriticController::class);
Route::apiResource('users', UserController::class);