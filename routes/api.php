<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Tweet;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/tweets', function (Request $request) {
    $request->validate([
        'body' => 'required'
    ]);

    return Tweet::create([
        'body' => $request->body,
        'user_id' => User::first()->id
    ]);
});

Route::get('/users/{user}', function(User $user){
    return $user->only(
        'id',
        'name',
        'username',
        'avatar',
        'profile',
        'location',
        'link',
        'link_text',
        'created_at'
    );
});

Route::get('users/{user}/tweets', function(User $user){
    return $user->tweets()->with('user:id,name,username,avatar')->latest()->paginate(10);
});
