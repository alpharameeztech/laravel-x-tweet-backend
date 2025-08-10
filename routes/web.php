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


Route::get('/ai/spam-check', function () {
    return view('spam-check');
});

Route::post('/ai/spam-check', function () {
    $attributes = request()->validate([
        'body' => ['required', 'string']
    ]);

    $response = \OpenAI\Laravel\Facades\OpenAI::chat()->create([
        'model' =>  'gpt-4.1-nano',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a forum moderator who always responds using JSON.'],
            [
                'role' => 'user',
                'content' => <<<EOT
                    Please inspect the following text and determine if it is spam.

                    {$attributes['body']}

                    Expected Response Example:

                    {"is_spam": true|false}
                    EOT
            ]
        ],
        'response_format' => ['type' => 'json_object']
    ])->choices[0]->message->content;

    $response = json_decode($response);

    // Trigger failed validation, display a flash message, abort...
    return $response->is_spam ? 'THIS IS SPAM!': 'VALID POST';
});
