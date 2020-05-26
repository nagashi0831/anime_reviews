<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Anime;
use Faker\Generator as Faker;

$factory->define(App\Anime::class, function (Faker $faker) {
    return [
        'title' => 'アニメのタイトル',
        'body' =>"本文です。テキストテキストテキストテキストテキストテ
        キストテキストテキストテキストテキスト。\nテキストテキストテキ
        ストテキストテキストテキストテキストテキストテキストテキスト。
        テキストテキストテキストテキストテキストテキストテキストテキス
        トテキストテキスト。",
    ];
});
