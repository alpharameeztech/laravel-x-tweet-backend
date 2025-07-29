<?php

use Illuminate\Support\Facades\Route;
use App\Models\Tweet;

Route::get('/', function () {
    return view('welcome');
});

Route::get('api/tweets', function(){
    return Tweet::with('user:id,name,username,avatar')->latest()->get();
});