<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CriticController;
use App\Http\Controllers\UserController;

Route::apiResource('films', FilmController::class);
Route::apiResource('actors', ActorController::class);
Route::get('/films/{id}/actors', [FilmController::class, 'getFilmActors']);
Route::get('/films/{id}/critics', [FilmController::class, 'getFilmCritics']);
Route::apiResource('languages', LanguageController::class);
Route::apiResource('critics', CriticController::class);
Route::apiResource('users', UserController::class);