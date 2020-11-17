@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h1 class="font-weight-bold text-danger">WELCOME TO THE あにれびゅ！</h1>
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-header">アカウントにログイン済です</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <a class="btn btn-outline-dark" href="/">とっぷぺーじに戻る</a>
                </div>
            </div>
            <div class="row mt-3">
                <a class="btn btn-success col-md-3 ml-3" href="/login/line">
                    LINEアカウントを連携
                </a>
                <a class="btn btn-primary col-md-3 ml-3" href="/login/twitter">
                    Twitterアカウントを連携
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
