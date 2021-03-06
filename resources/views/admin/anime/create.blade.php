@extends('layouts.admin')

@section('title', 'レビュー投稿')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h2 class="text-body">レビュー投稿フォーム</h2>
            <form action="{{ action('Admin\AnimeController@create') }}"
             method="post" enctype="multipart/form-data">
                @if (count($errors) > 0)
                <ul>
                    @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
                @endif
                <div class="form-group row">
                    <label class="col-md-2">
                        タイトル
                    </label>
                    <div class="col-md-10 searchArea">
                        <input type="text" class="searchTitle form-control" name="title" 
                        value="{{ old('title') }}" autocomplete="off">
                        <div class="searchResultNum"></div>
                        <div class="searchResultList"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2">
                        カテゴリー
                    </label>
                    <div class="col-md-5">
                        <select name="category">
                            <option value="">選択してください</option>
                            <option value="action">アクション/バトル</option>
                            <option value="love">恋愛/ラブコメ</option>
                            <option value="comedy">コメディ/ギャグ</option>
                            <option value="other">その他</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2">
                        紹介文
                    </label>
                    <div class="col-md-10">
                        <textarea class="form-control" name="body" 
                        rows="20">{{ old('body') }}</textarea>
                        
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2">
                     画像（*任意）
                    </label>
                    <div class="col-md-10">
                        <input type="file" class="form-control-file"
                         name="image">
                    </div>
                </div>
                {{ csrf_field() }}
                <input type="submit" class="btn btn-primary" 
                value="更新">
            </form>
        </div>
    </div>
</div>
@foreach ($titles as $title)
<ul class="test" hidden>
    <li>{{ $title }}</li>
</ul>
@endforeach
<script type="module" src="{{ asset('js/admin.js') }}"></script>
@endsection