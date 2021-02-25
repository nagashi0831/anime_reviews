<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
use App\User;
use App\Anime;
use App\Like;
use Storage;
use phpQuery;
use Goutte\Client;
use Illuminate\Support\Facades\Auth;

//デプロイ先のxserverのファイルではhome/nagashi0831/reviewanime.work/anime_reviews/vendor/autoload.phpとなっているので、競合が起きないように注意
require_once("/home/ec2-user/environment/anime_review/vendor/autoload.php");

class AnimeController extends Controller
{
    public function add()
    {
        $client = new Client();
        $japaSpells = ["あ行" ,"か行" ,"さ行" ,"た行" ,"な行" ,"は行" ,"ま行" ,"や行" ,"ら行" ,"わ行" ,"アルファベット"];
        /*$japaSpells = ["a" ,"i" ,"u" ,"e" ,"o" ,"ka" ,"ki" ,"ku" ,"ku" ."ke" ,"ko" ,"sa" ,"shi" ,"su" ,"se" ,"so" ,
        "ta" ,"chi" ,"tsu" ,"te" ,"to" ,"na" ,"ni" ,"nu" ,"ne" ,"no" ,"ha", "hi" ,"fu" ,"he" ,"ho" ,"ma" ,"mi" ,"mu" ,
        "me" ,"mo" ,"ya" ,"yu" ,"yo" ,"ra" ,"ri" ,"ru" ,"re" ,"ro" ,"wa" ,"wo" ,"n"];*/
        $titles = array();
        
        foreach ($japaSpells as $japaSpell) {
            $url = "https://ja.wikipedia.org/wiki/日本のテレビアニメ作品一覧_".$japaSpell;
            
            $scraping = $client->request('GET', $url);
            $title = $scraping->filter('body')->filter('.mw-body')->filter('#bodyContent')
            ->filter('#mw-content-text')->filter('.mw-parser-output')
            ->each(function ($title) use (&$titles){
                
                $title->filter('ul')
                ->each(function ($title) use (&$titles){
                    $title->filter('li')
                    ->each(function ($title) use (&$titles){
                        $title = $title->filter('a');
                        if (count($title)) {
                        array_push($titles, $title->text());
                        }
                    });
                });
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
        $like_model = new Like;
        
        if($cond_title != ''){
            //検索されたら検索結果を取得する
            $posts = Anime::where('title',$cond_title)->paginate(10);
        } elseif($request->category != null){
            $posts = Anime::where('category',$request->category)->orderBy('created_at','desc')->paginate(10);
        } else{
            //それ以外はすべての投稿を取得する
            $posts = Anime::orderBy('created_at','desc')->paginate(10);
        }
        
        foreach ($posts as $post) {
            $likes_count = Anime::findOrFail($post->id)->loadCount('likes')->likes_count;
            $post->likes_count = $likes_count;
        }
        Log::debug($posts);
        return view('admin.anime.index',['posts' => $posts, 
        'users' => $users,
        'like_model' => $like_model,
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
    
    public function ajaxlike(Request $request)
    {
        $id = Auth::user()->id;
        $anime_id = $request->anime_id;
        $like = new Like;
        $post = Anime::findOrFail($anime_id);
        
        //既に見たことある！しているなら
        if ($like->like_exist($id, $anime_id)) {
            //likesテーブルのレコードを削除
            $like = Like::where('anime_id', $anime_id)->where('user_id', $id)->delete();
        } else {
            //まだ見たことある！していないならlikesテーブルに新しいレコードを作成する
            $like = new Like;
            $like->anime_id = $request->anime_id;
            $like->user_id = Auth::user()->id;
            $like->save();
        }
        
        //loadCountとすればリレーションの数を##_countという形で取得できる
        $postLikesCount = $post->loadCount('likes')->likes_count;
        
        //1つの変数にajaxに渡す値をまとめ
        $json = [
            'postLikesCount' => $postLikesCount,
            ];
            //下記の記述でajaxに引数の値を返す
            return response()->json($json);
    }
    
}
