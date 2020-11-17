<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = array('id');
    public static $rules = array(
        'message' => 'required',
        );
}
