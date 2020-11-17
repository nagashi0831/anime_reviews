@extends('layouts.admin')

@section('content')
<div class="container">
    <a class="card-link" href="{{ action('Admin\AnimeController@index') }}">
        ↩アニメ一覧に戻る
    </a>
    <div class="border p-4">
        <div class="show-message">
            <h1 class="h5 mb-4">
                {{ $post->title }}
            </h1>
            
            <p class="mb-5">
                {{ \Str::limit($post->body, 250) }}
            </p>
            {{--編集をクリック=>edit actionを呼び出す=>edit.blade.phpを呼び出して編集画面の表示--}}
            
            <section>
                <h2 class="h5 mb-4">
                    コメント
                </h2>
        </div>
            <form class="mb-4" method="POST" action="{{ url('/admin/anime/comment') }}">
                @csrf
                <input
                    name="anime_id"
                    type="hidden"
                    value="{{ $post->id }}"
                >
            
                <div class="form-group">
                    <label for="body">
                        本文
                    </label>
            
                    <textarea
                        id="body"
                        name="body"
                        class="form-control {{ $errors->has('body') ? 'is-invalid' : '' }}"
                        rows="4"
                    >{{ old('body') }}</textarea>
                    @if ($errors->has('body'))
                        <div class="invalid-feedback">
                            {{ $errors->first('body') }}
                        </div>
                    @endif
                </div>
            
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        コメントする
                    </button>
                </div>
            </form>
            @forelse($post->comments as $comment)
            <div class="border-top p-4">
                <time class="text-secondary">
                    {{ $comment->created_at->format('Y.m.d H:i') }}
                </time>
                <p class="mt-2">
                    {{ \Str::limit($comment->body, 100) }}
                </p>
            </div>
            @empty
            <p>コメントはまだありません</p>
            @endforelse
        </section>
    </div>
</div>
@endsection
