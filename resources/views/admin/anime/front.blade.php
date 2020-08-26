@extends('layouts.front')


@section('title', 'あにれびゅ！')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="hero-img" style="background-image:url('/storage/image/hero-img.png?version=20200826');">
            <div class="cover-text text-center">
                <ul>
                    <li>
                    <h1>
                        あにれびゅ！
                    </h1>
                    </li>
                    
                    <li class="logo-under-item">
                        <a href="{{ action('Admin\AnimeController@add') }}" class="text-white bg-dark create-link rounded">
                            レビューを投稿する
                        </a>
                        <a href="{{ action('Admin\AnimeController@index') }}" class="text-white bg-dark create-link rounded">
                            レビューいちらん
                        </a>
                    </li>
                </ul>    
            </div>
        </div>
    </div>
</div>
<div class="container">
    <h2 class="col-xl-5">
        サイトについて
    </h2>
    <h3>
        おススメのアニメを紹介しよう
    </h3>
    <h4>
        <br>最終回を見て感動した後、他の誰かにも見てもらいたい...
        <br>そう思う瞬間はありませんか？？
        <br><a href="/register">会員登録</a>をして、自分の中でトレンド入りしているアニメを投稿してみましょう！
        <br>それを見て興味を持ってくれた人がコメントをくれるかも...?
        <br>感動をみんなで共有しましょう！
    </h4>
    <img class="col-xl-12" src="/storage/image/anireview_info.png?20200826">
    <a href="/storage/image/anireview_info.png">上記の画像が小さいときはコチラをクリック</a>
    
    <h3>
        会員登録をしたらLINEアカウント認証も済ませておこう
    </h3>
    <h4>
        <br>会員登録はお済みですか？
        <br>そうしましたら<a href="/login/line">LINEアカウント認証</a>をして、次回から手軽にログインできるようにしてみましょう！
        <br>※まずは<a href="/register">コチラで</a>会員登録をしてから行ってください
    </h4>
</div>
@endsection