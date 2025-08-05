<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use App\AI\Chat;
use App\AI\OpenAIChat;

Route::get('/', function () {
    return view('welcome');
});

Route::get('api/tweets', function(){
    return Tweet::with('user:id,name,username,avatar')->latest()->paginate(10);
});

Route::get('api/tweets/{tweet}', function(Tweet $tweet){
    return $tweet->load('user:id,name,username,avatar');
});


// Route::get('ai',function(){
//
//     $response = Http::withHeaders([
//         'Content-Type' => 'application/json',
//         'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
//     ])->post('https://api.openai.com/v1/responses', [
//         'model' => 'gpt-4.1-nano',
//         'input' => 'Write a funny poem about husband and wife that fights all the time but cannot live without each other.',
//     ]);
// return $response;
// });


Route::get('ai', function () {
// using Chat class
//     $chat = New Chat();
//     $joke = $chat
//         ->systemMessage('Create a joke about husband and wife')
//         ->send('Create a nice joke');
//
//         logger('joke 1');
//         logger($joke);
//
//         $joke2 = $chat->reply('thats cool but it should be more silly');
//
//         logger('joke 2');
//         logger($joke2);
//
//     return $joke2;


//   using open ai chat helper
    $chat = New OpenAIChat();
    $joke = $chat
        ->systemMessage('Create a nice quote to keep me moving')
        ->send('Create a nice joke');

        logger('joke 1');
        logger($joke);

        $joke2 = $chat->reply('Make it more nice');

        logger('joke 2');
        logger($joke2);

    return $joke2;


});
