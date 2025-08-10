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


Route::get('/roast', function () {
    return view('roast');
});
Route::post('/ai/roast', function () {
    $attributes = request()->validate([
        'topic' => [
            'required', 'string', 'min:2', 'max:50'
        ]
    ]);

    $mp3 = (new OpenAIChat())->send(
        message: "Please roast {$attributes['topic']} in a funny and sarcastic tone.",
        speech: true
    );

    $filename = md5($mp3) . '.mp3';
    $file = 'roasts/' . $filename;
    file_put_contents(public_path($file), $mp3);

    return redirect('/roast')->with([
        'file'  => '/' . $file,
        'flash' => 'Boom. Roasted.'
    ]);
});

Route::get('/image', function () {
    return view('image', [
        'messages' => session('messages', [])
    ]);
});

Route::post('/image', function () {
    $attributes = request()->validate([
        'description' => ['required', 'string', 'min:3']
    ]);

    $assistant = new \App\AI\Assistant(session('messages', []));

    $assistant->visualize($attributes['description']);

    session(['messages' => $assistant->messages()]);

    return redirect('/image');
});


