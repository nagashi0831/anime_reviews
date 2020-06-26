<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = array('id');
    public static $rules = array(
        'body' => 'required',
        );
        
        public function post()
        {
            return $this->belongsTo('App\Anime');
            
            
        }
}
