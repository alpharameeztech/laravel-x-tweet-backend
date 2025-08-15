<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserTweetsController extends Controller
{
    public function index(User $user){
        return $user->tweets()->with('user:id,name,username,avatar')->latest()->paginate(10);
    }
}
