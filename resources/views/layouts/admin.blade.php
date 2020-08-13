<!DOCTYPE html>
<!-- {{ app()->getLocale() }}はconfig/appでの言語設定を呼び出す -->
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset = "utf-8">
        <meta http-equiv="X-UA-Compatible"
        content="IE-edge">
        <!-- レスポンシブ -->
        <meta name="viewport" content="width=device-width,
        initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <script src="{{ secure_asset('js/app.js') }}" defer></script>
        
        <link rel="dns-prefetch" http="https://fonts.gstatic.com">
        <!-- Google Web Fontsを使うためのAPI接続 -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600"
        rel="stylesheet" type="text/css">
        
        <link href="{{ secure_asset('css/app.css') }}"
        rel="stylesheet">
        <link href="{{ secure_asset('css/admin.css') }}"
        rel="stylesheet">
    </head>
    <body>
        <!-- ナビゲーションバーの設定 -->
        <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-dark">
          <a class="navbar-brand nav-logo" href="#">あにれびゅ！</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
                <a class="nav-link" href="/">とっぷぺーじ<span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ action('Admin\AnimeController@index', ['category' => 'action']) }}">アクション/バトル</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ action('Admin\AnimeController@index', ['category' => 'love']) }}">恋愛/ラブコメ<span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ action('Admin\AnimeController@index', ['category' => 'comedy']) }}">コメディ/ギャグ</a>
              </li>
              <li class="nav-item">
                <a class="nav-link disabled" href="{{ action('Admin\AnimeController@index', ['category' => 'other']) }}">その他</a>
              </li>
              @guest
              {{--以下のLoginは画面右上に表示されるLoginを指している。--}}
              <li class="nav-item"><a  class="nav-link" href="{{ route('login') }}">{{ __('ログイン') }}</a></li>
              {{-- ログインしていたらユーザー名とログアウトボタンを表示 --}}
              @else
              <li class="nav-item dropdown">
                  <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#"
                   role="button" data-toggle="dropdown" aria-haspopup="true" 
                   aria-expanded="false" v-pre>
                      {{ Auth::user()->name }} <span class="caret"></span>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="{{ route('logout') }}" 
                      onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                          {{ __('ログアウト') }}
                      </a>
                      
                      <form id="logout-form"
                       action="{{ route('logout') }}" method="POST" 
                       style="display: none;">
                          @csrf
                      </form>
                      
                      <a class="dropdown-item" href="{{ action('Admin\UsersController@mypost') }}">
                          {{ __('私の投稿いちらん') }}
                      </a>
                  </div>
              </li>
              @endguest
            </ul>
            <form class="form-inline my-2 my-lg-0">
              <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
          </div>
        </nav>
            <main class="py-4">
                @yield('content')
            </main>
    </body>
</html>