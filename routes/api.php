<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\User;

Route::post('/tweets', function (Request $request) {
    $request->validate([
        'body' => 'required'
    ]);

    return Tweet::create([
        'body' => $request->body,
        'user_id' => User::first()->id 
    ]);
});
