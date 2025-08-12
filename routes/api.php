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
        'user_id' => auth()->id(),
        'body' => $request->body,
    ]);
})->middleware('auth:sanctum');

Route::get('/tweets_all', function () {
    return Tweet::with('user:id,name,username,avatar')->latest()->paginate(10);
});

Route::get('/tweets', function () {
    $followers = auth()->user()->follows->pluck('id');

    return Tweet::with('user:id,name,username,avatar')->whereIn('user_id', $followers)->latest()->paginate(10);
})->middleware('auth:sanctum');

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

Route::post('/follow/{user}', function (User $user) {
    auth()->user()->follow($user);

    return response()->json('Followed',201);
})->middleware('auth:sanctum');

Route::post('/unfollow/{user}', function (User $user) {
    auth()->user()->unFollow($user);

    return response()->json('UnFollowed',201);
})->middleware('auth:sanctum');

Route::delete('/tweets/{tweet}', function (Tweet $tweet) {

    abort_if($tweet->user->id !== auth()->id(),403);

    return response()->json($tweet->delete(),201);
})->middleware('auth:sanctum');

Route::get('is_following/{user}', function (User $user) {

    return response()->json( auth()->user()->isFollowing($user),200);

})->middleware('auth:sanctum');

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

Route::post('/register', function (Request $request) {
    $request->validate([
        'name'=> 'required|min:3',
        'email' => 'required|email|unique:users',
        'username' => 'required|min:4|unique:users',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'username' => $request->username,
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
    ]);

    $user->follows()->attach($user);

    return response()->json($user,201);
});

Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json('Logged out',200);
})->middleware('auth:sanctum');
