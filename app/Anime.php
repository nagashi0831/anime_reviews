<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    protected $guarded = array('id');
    public static $rules = array(
        'title' => 'required',
        'body' => 'required',
        'category' => 'required',
        );
        
        public function user(){
            return $this->belongsTo('App\User');
        }
        
        public function comments()
        {
            return $this->hasMany('App\Comment');
            
        }
}
