<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\TweetAllController;
use App\Http\Controllers\UserFollowController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserTweetsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tweets', [TweetController::class, 'index'])->name('tweet.index');
    Route::get('/tweets_all', [TweetAllController::class, 'index'])->name('tweet.index.all');
    Route::get('/tweets/{tweet}', [TweetController::class, 'show'])->name('tweet.show');
    Route::post('/tweets', [TweetController::class, 'store'])->name('tweet.store');
    Route::delete('/tweets/{tweet}', [TweetController::class, 'destroy'])->name('tweet.delete');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/follow/{user}', [UserFollowController::class, 'store'])->name('user.follow');
    Route::post('/unfollow/{user}', [UserFollowController::class, 'destroy'])->name('user.unfollow');
    Route::get('/is_following/{user}', [UserFollowController::class, 'isFollowing'])->name('user.isFollowing');
});

Route::get('/users/{user}', [UserProfileController::class, 'show'])->name('user.profile.show');
Route::get('/users/{user}/tweets', [UserTweetsController::class, 'index'])->name('user.tweets.index');


Route::post('/login', [\App\Http\Controllers\AuthController::class, 'store'])->name('login');
Route::post('/register', [\App\Http\Controllers\RegisterController::class, 'store'])->name('register');
Route::middleware('auth:sanctum')->post('/logout', [\App\Http\Controllers\AuthController::class, 'destroy'])->name('logout');
