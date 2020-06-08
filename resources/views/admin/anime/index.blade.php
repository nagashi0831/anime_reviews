@extends('layouts.admin')
@section('title', 'レビュー作品一覧')

@section('content')
   <div class="container mt-4">
      <div class="mb-4">
         <a href="{{ action('Admin\AnimeController@add') }}" role="button" class="btn btn-primary">
            新規作成
            </a>
      </div>
       @foreach ($posts as $post)
       {{-- この$postsはAnimeController@indexで定義された->all()
       によってレビュー内容が格納された変数--}}
       <div class="card mb-4">
           <div class="card-header">
               {{ $post->title }} {{--アニメのタイトル--}}
           </div>
           <div class="card-body">
               <p class="card-text">
                   {{ \Str::limit($post->body, 250) }}
               </p>
               
               <a class="card-link" href="{{ action('Admin\AnimeController@show') }}">
                  続きを読む
               </a>
           </div>
           <div class="card-footer">
               <span class="mr-2">
                   投稿日時 {{ $post->updated_at->format('Y.m.d') }}
               </span>
               
               @if ($post->comments->count())
               <span class="badge badge-primary">
                  　コメント {{ $post->comments->count() }}件
               </span>
               @endif
               <div>
                {{-- action('Admin\AnimeController@edit',['id' => $anime->id]) とするとエラー発生したので、['id' => ~~] を消した。--}}
                <a href="{{ action('Admin\AnimeController@edit',['id' => $post->id])
                 }}">編集</a>
            　　</div>
            　　<div>
            　　 <a href="{{ action('Admin\AnimeController@delete',"   
            　　</div>
           </div>
       </div>
       @endforeach
   </div>
  @endsection