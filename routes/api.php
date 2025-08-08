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


Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $token = $user->createToken($request->device_name)->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user->only('id','name','username','avatar')
    ], 201);
});
