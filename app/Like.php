<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //見たことある！しているユーザー
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    //見たことある！している投稿
    public function anime()
    {
        return $this->belongsTo('App\Anime');
    }
    
    //見たことある！がすでにされているかを確認
    public function like_exist($id, $anime_id)
    {
        //Likesテーブルのレコードにユーザーidと投稿idが一致するものを取得
        $exists = Like::where('user_id', '=', $id)->where('anime_id', '=', $anime_id)->get();
        
        //レコードexitsが存在するなら
        if (!$exists->isEmpty()) {
            return true;
        } else {
            return false;
        }
    }
}
