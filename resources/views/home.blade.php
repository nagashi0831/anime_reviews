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

                    You are logged in!
                </div>
                <div class="col-md-8 col-md-offset-4">
                    <a class="btn btn-default" href="/login/line">
                        LINEアカウントを追加する
                    </a>
                    <a class="btn btn-default" href="/login/twitter">
                        Add twitter Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
