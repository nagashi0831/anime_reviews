<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    protected $guarded = array('id');
    public static $rules = array(
        'title' => 'required',
        'body' => 'required',
        );
        
        public function comments()
        {
            return $this->hasMany('App\Comment');
            
        }
}
