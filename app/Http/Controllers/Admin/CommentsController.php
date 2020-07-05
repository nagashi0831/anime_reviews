<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Anime;
use App\Comment;

class CommentsController extends Controller
{
    public function store(Request $request){
            $this->validate($request, Comment::$rules);
            
            $comment = new Comment;
            $form = $request->all();
            
            $comment->fill($form);
            $comment->save();
            return redirect('admin/anime/comment?id='.$comment->anime_id);
    }
}
//
