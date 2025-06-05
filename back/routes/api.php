<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsurePostOwner;
use App\Http\Middleware\EnsureCommentOwner;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class)
        ->only(['index', 'show', 'store']);
    Route::middleware(EnsurePostOwner::class)->group(function () {
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    });
    Route::get('/categories', fn() => Category::all());
    Route::get('/user/posts', [PostController::class, 'myPosts']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware(EnsureCommentOwner::class);;
});

