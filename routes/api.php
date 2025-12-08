<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    StoryController,
    ChapterController,
    CommentController,
    LikeController,
    FollowController
};

Route::middleware('auth:sanctum')->group(function () {

    // Stories
    Route::get('/stories', [StoryController::class, 'index']);
    Route::post('/stories', [StoryController::class, 'store']);
    Route::get('/stories/{story}', [StoryController::class, 'show']);
    Route::put('/stories/{story}', [StoryController::class, 'update']);
    Route::delete('/stories/{story}', [StoryController::class, 'destroy']);

    // Chapters
    Route::post('/chapters', [ChapterController::class, 'store']);
    Route::get('/chapters/{chapter}', [ChapterController::class, 'show']);
    Route::put('/chapters/{chapter}', [ChapterController::class, 'update']);
    Route::delete('/chapters/{chapter}', [ChapterController::class, 'destroy']);

    // Comments
    Route::post('/comments', [CommentController::class, 'store']);

    // Likes & Follow
    Route::post('/likes/toggle', [LikeController::class, 'toggle']);
    Route::post('/follows/toggle', [FollowController::class, 'toggle']);
});
