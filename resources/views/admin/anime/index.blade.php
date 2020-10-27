@extends('layouts.admin')
@section('title', 'レビュー作品一覧')

@section('content')
   <div class="container mt-4">
      <div class="pt-4 pb-4">
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
           @if ($post->image_path != null)
           <img src="/storage/image/{{ $post->image_path }}" width="200px"
            height="100%">
            @endif
           <div class="card-body">
               <p class="card-text">
                   {{ \Str::limit($post->body, 250) }}
               </p>
               <a class="card-link" href="{{ action('Admin\AnimeController@show',['id' => $post->id]) }}">
                  続きを読む・コメント一覧
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
           </div>
       </div>
       @endforeach
       <div class="d-flex justify-content-center mb-5">
           {{ $posts->links() }}
       </div> 
   </div>
  @endsection