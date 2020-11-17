@extends('layouts.admin')
@section('title', 'レビュー作品一覧')

@section('content')
   <div class="container">
      <div class="pb-4">
         <a href="{{ action('Admin\AnimeController@add') }}" role="button" class="btn btn-primary">
            新規作成
        </a>
        <p class="float-right indexFukidashi">投稿者の名前をクリックすればDMが送れるよ！</p>
      </div>
       @foreach ($posts as $post)
       {{-- この$postsはAnimeController@indexで定義された->all()
       によってレビュー内容が格納された変数--}}
       <div class="card mb-4">
            <div class="card-header">
                <span class="float-left font-weight-bold">
                   {{ $post->title }} {{--アニメのタイトル--}}
                </span>
                
                {{-- 投稿者と、メッセージを送れるドロップダウンメニュー --}}
                <span class="float-right">
                @foreach ($users as $user)
                    @if ($post->user_id == $user->id)
                        @if ($post->user_id != Auth::id() ) 
                            <span class="dropdown">
                                <button class="btn dropdown-toggle p-0" type="button" data-toggle="dropdown">
                                    {{ $user->name }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdown-menu">
                                    <a class="dropdown-item" href="/admin/anime/message/{{ $user->id }}">投稿主とチャットをする</a>
                                </div>
                            </span>
                        @else
                            {{ $user->name }}
                        @endif
                    @endif
                @endforeach
                </span>
                
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