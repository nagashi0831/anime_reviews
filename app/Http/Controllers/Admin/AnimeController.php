<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Anime;
class AnimeController extends Controller
{
    public function add()
    {
        return view('admin.anime.create');
    }
    
    public function create(Request $request){
        
        $this->validate($request, Anime::$rules);
        $anime = new Anime;
        $form = $request->all();
        
        if (isset($form['image'])){
            $path = $request->file('image')->store('public/image');
            $anime->image_path = basename($path);
        } else{
            $anime->image_path = null;
        }
        
        unset($form['_token']);
        unset($form['image']);
        $anime->fill($form);
        $anime->save();
        return redirect('admin/anime/create');
    }
    
    public function test()
    {
        //いつ消してもおけ
        return view('admin.anime.test');
    } 
    
    public function index(Request $request)
    {
        //cond_titleは検索窓の内容を取得
        $cond_title = $request->cond_title;
        
        if($cond_title != ''){
            //検索されたら検索結果を取得する
            $posts = Anime::where('title',$cond_title)->get();
        } else{
            //それ以外はすべてのニュースを取得する
            $posts = Anime::all();
        }
       return view('admin.anime.index',['posts' => $posts, 
       'cond_title' =>$cond_title]);
    }
    
    public function show($anime_id=1)
    {
        $post = Anime::findOrFail($anime_id);
        return view('admin.anime.show',[
            'post' => $post,
            ]);
        
    }
    
}
