<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Anime;

class CommentsController extends Controller
{
    public function store(Request $request){
            $params = $request->validate([
                'anime_id' => 'required|exists:animes,id',
                'body' => 'required|max:2000',
                ]);
                
                $post = Anime::findOrFail($params['anime_id']);
                $post->comments()->create($params);
                return redirect('admin.anime.show', ['post' => $post]);

                
    }
}
