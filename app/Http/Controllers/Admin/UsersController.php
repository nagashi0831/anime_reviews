<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//下の行はログイン情報を取得するためのもの
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Anime;

class UsersController extends Controller
{
     public function mypost(User $user){
         $user = Auth::user();
         $posts = Anime::where('user_id',$user->id)
         ->orderBy('created_at','desc')
         ->paginate(10);
         return view('admin.anime.mypost',[
             'user_name' => $user->name, //$user名をviewへ渡す
             'posts' => $posts  //$userの書いた投稿をviewへ渡す
             ]);
    }
}
