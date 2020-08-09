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
        <link href="{{ secure_asset('css/front.css') }}"
        rel="stylesheet">
    </head>
    <body>
        <header>あにれびゅ！</header>
        <!-- ナビゲーションバーはレスポンシブで幅が変わる -->
        <nav>
            <ul>
            <li><a href="/">とっぷぺーじ</a></li>
            <li><a href="#">アクション/バトル</a></li>
            <li><a href="#">恋愛/ラブコメ</a></li>
            <li><a href="#">コメディ/ギャグ</a></li>
            <li><a href="#">その他</a></li>
            {{--ログインしていなかったらログイン画面へのリンクを表示--}}
            @guest
            {{--以下のLoginは画面右上に表示されるLoginを指している。--}}
            <li><a href="{{ route('login') }}">{{ __('ログイン') }}</a></li>
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
        </nav>
            <main class="py-4">
                @yield('content')
            </main>
        
    </body>
</html>