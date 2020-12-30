<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
use App\User;
use App\Anime;
use Storage;
use phpQuery;
use Goutte\Client;

require_once("/home/ec2-user/environment/anime_review/vendor/autoload.php");

class AnimeController extends Controller
{
    public function add()
    {
        $client = new Client();
        $japaSpells = ["a" ,"i" ,"u" ,"e" ,"o" ,"ka" ,"ki" ,"ku" ,"ku" ."ke" ,"ko" ,"sa" ,"shi" ,"su" ,"se" ,"so" ,
        "ta" ,"chi" ,"tsu" ,"te" ,"to" ,"na" ,"ni" ,"nu" ,"ne" ,"no" ,"ha", "hi" ,"fu" ,"he" ,"ho" ,"ma" ,"mi" ,"mu" ,
        "me" ,"mo" ,"ya" ,"yu" ,"yo" ,"ra" ,"ri" ,"ru" ,"re" ,"ro" ,"wa" ,"wo" ,"n"];
        $titles = array();
        foreach ($japaSpells as $japaSpell) {
            $url = "https://anime.nicovideo.jp/search/anime/".$japaSpell.".html?from=nanime_anime-search_50";
            $scraping = $client->request('GET', $url);
            
            $title = $scraping->filter('body')->filter('div')->filter('#__layout')
            ->filter('div')->filter('._2jT-H')->filter('main')->filter('.sa__search-results')
            ->filter('.sa__search-results__inner')->filter('ul')->filter('li')
            ->each(function ($title) use (&$titles){
                $title = $title->filter('a');
                if (count($title)) {
                    array_push($titles, $title->text());
                }
            });
        }
        
        return view('admin.anime.create', compact('titles'));
    }
    
    public function create(Request $request){
        
        $this->validate($request, Anime::$rules);
        $anime = new Anime;
        //下記のuser()はAnime　Modelのuser()関数 関連するUserインスタンスのidプロパティをAnimeインスタンスのuser_idプロパティとして保存している。
        $anime->user_id = $request->user()->id;
        $form = $request->all();
        // フォームから画像が送信されてきたら、保存して、$anime->image_path に画像のパスを保存する
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
        return redirect('admin/anime/');
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
        $users = User::get();
        
        if($cond_title != ''){
            //検索されたら検索結果を取得する
            $posts = Anime::where('title',$cond_title)->paginate(10);
        } elseif($request->category != null){
            $posts = Anime::where('category',$request->category)->orderBy('created_at','desc')->paginate(10);
        } else{
            //それ以外はすべての投稿を取得する
            $posts = Anime::orderBy('created_at','desc')->paginate(10);
        }
       return view('admin.anime.index',['posts' => $posts, 
       'users' => $users,
       'cond_title' =>$cond_title]);
    }
    
    public function show(Request $request)
    {
        $post = Anime::find($request->id);
        if (empty($post)) {
            abort(404);
        }
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
    
    
    public function delete(Request $request){
        //該当するAnime Modelを取得
        $anime = Anime::find($request->id);
        //削除する
        \DB::transaction(function() use($anime){
        $anime->comments()->delete();    
        $anime->delete();
        });
        
        return redirect('admin/anime');
        
    }
    
    public function front(Request $request){
        //get()でデータ取得
        $posts = Anime::orderBy('created_at','desc')->get();
        return view('admin.anime.front',['posts' => $posts]);
    }
    
}
