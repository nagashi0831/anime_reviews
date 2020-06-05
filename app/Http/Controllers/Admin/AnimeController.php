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
    //編集ボタンを押したときに呼び出されるアクション。idが見つからなかったときにabort(404)を出力する
    public function edit(Request $request){
        $anime = Anime::find($request->id);
        if (empty($anime)) {
            abort(404);
        }
        return view('admin.anime.edit',['anime_form' => 
        $anime]);
    }
    
    public function update(Request $request){
        //validateをかける
        $this->validate($request, Anime::$rules);
        // Anime Modelからデータを取得する
        $anime = Anime::find($request->id);
        // 送信されてきたフォームデータを格納する
        $anime_form = $request->all();
        //imageに関してはedit.blade.php line43参照
        if (isset($anime_form['image'])) {
            $path = $request->file('image')
            ->store('public/image');
            $anime->image_path = basename($path);
            //変更前の画像データを削除
            unset($anime_form['image']);
            //removeに関してはedit.blade.php line50参照
        } elseif (isset($request->remove)) {
            $anime->image_path = null;
            unset($anime_form['remove']);
        }
        unset($anime_form['_token']);
        
        //該当するデータを上書きして保存する
        $anime->fill($anime_form)->save();
        
        return redirect('admin/anime');
    }
    
}
