<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CriticController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::apiResource('films', FilmController::class);
Route::get('/films/{id}/actors', [FilmController::class, 'getFilmActors']);
Route::get('/films/{id}/critics', [FilmController::class, 'getFilmCritics']);
Route::get('films/{id}/average-score', [FilmController::class, 'averageScore']);
Route::get('films-search', [FilmController::class, 'search']);
Route::apiResource('critics', CriticController::class);
Route::apiResource('users', UserController::class);
Route::get('users/{id}/preferred-language', [UserController::class, 'preferredLanguage']);

Route::middleware('throttle:5,1')->group(function () {
    Route::post('/signup', [AuthController::class, 'register']);
    Route::post('/signin', [AuthController::class, 'login']);
});
Route::middleware(['auth:sanctum', 'throttle:5,1'])->post('/signout', [AuthController::class, 'logout']);

