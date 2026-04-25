<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    StoryController,
    UserController,
    ChapterController,
    CommentController,
    LikeController,
    FollowController
};
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\UserFollowController;

// Rutas públicas
Route::get('/stories', [StoryController::class, 'index']);
Route::get('/stories/{story}', [StoryController::class, 'show']);
Route::get('/genres', [GenreController::class, 'index']);

Route::get('/users/{user}', [UserController::class, 'show']);
Route::get('/users/{user}/stories', [UserController::class, 'stories']);
Route::get('/users/{user}/follows', [UserController::class, 'follows']);
Route::get('/users/{user}/comments', [UserController::class, 'comments']);

Route::get('/chapters/{chapter}', [ChapterController::class, 'show']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'loginWithGoogle']);

Route::get('/users/{user}/follow/status', [UserFollowController::class, 'status']);

// Rutas protegidas por Sanctum (requieren usuario autenticado)
Route::middleware('auth:sanctum')->group(function () {
    // Perfil propio
    Route::get('/me', [UserController::class, 'me']);
    Route::put('/me', [UserController::class, 'update']);
    Route::delete('/me', [UserController::class, 'destroy']);
    Route::get('/me/liked-stories', [UserController::class, 'likedStories']);

    // Stories
    Route::post('/stories', [StoryController::class, 'store']);
    Route::put('/stories/{story}', [StoryController::class, 'update']);
    Route::delete('/stories/{story}', [StoryController::class, 'destroy']);

    // Chapters
    Route::post('/chapters', [ChapterController::class, 'store']);
    Route::put('/chapters/{chapter}', [ChapterController::class, 'update']);
    Route::delete('/chapters/{chapter}', [ChapterController::class, 'destroy']);

    // Likes
    Route::post('/likes/toggle', [LikeController::class, 'toggle']);

    // Follows
    Route::post('/follows/toggle', [FollowController::class, 'toggle']);
    Route::post('/users/{user}/follow/toggle', [UserFollowController::class, 'toggle']);

    // Comments
    Route::post('/comments', [CommentController::class, 'store']);
});
