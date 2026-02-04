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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [UserController::class, 'me']);
    Route::put('/me', [UserController::class, 'update']);
    Route::delete('/me', [UserController::class, 'destroy']);
});

Route::get('/users/{user}', [UserController::class, 'show']);
Route::get('/users/{user}/stories', [UserController::class, 'stories']);
Route::get('/users/{user}/follows', [UserController::class, 'follows']);
Route::get('/users/{user}/comments', [UserController::class, 'comments']);
