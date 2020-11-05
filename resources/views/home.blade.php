@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-header">登録完了！</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    ログイン完了です！
                </div>
                <div class="col-md-8 col-md-offset-4">
                    <a class="btn btn-success" href="/login/line">
                        LINEアカウントを連携させる
                    </a>
                    <a class="btn btn-primary" href="/login/twitter">
                        Twitterアカウントを連携させる
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
