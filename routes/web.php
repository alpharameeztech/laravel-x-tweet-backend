<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 
use App\Models\Tweet;
use App\Models\User; 

Route::get('/', function () {
    return view('welcome');
});

Route::get('api/tweets', function(){
    return Tweet::with('user:id,name,username,avatar')->latest()->paginate(10);
});

Route::get('api/tweets/{tweet}', function(Tweet $tweet){
    return $tweet->load('user:id,name,username,avatar');
});

