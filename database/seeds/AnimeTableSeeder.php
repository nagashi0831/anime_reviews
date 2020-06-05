<?php

use Illuminate\Database\Seeder;
use App\Anime;
use App\Comment;

class AnimeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Anime::class, 50)
        ->create()
        ->each(function ($post) {
            $comments = factory(Comment::class, 20)->make();
            $post->comments()->saveMany($comments);
        });
    }
}
