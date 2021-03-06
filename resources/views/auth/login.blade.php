@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="login-box card mt-4">
                <div class="login-header card-header mx-auto">{{ __('messages.Login') }}</div>

                <div class="login-body card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('messages.E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ?' is-invalid' : '' }}"
                                 name="email" value="{{ old('email') }}" required autofocus>
                                 
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('messages.Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid'
                                 : '' }}" name="password" required>

                                @if($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="check-box">
                                    <label class="form-check-label" for="remember">
                                    <input type="checkbox"  name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        {{ __('messages.Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        @if($errors->has('social'))
                        <div class="form-group-row text-danger">
                            {{ $errors->first('social') }}
                        </div>
                        <div class="form-group-row text-primary">
                        @elseif(isset($success))
                            {{ $success }}
                        </div> 
                        @endif
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('messages.Login') }}
                                    
                                </button>
                                <a class="btn btn-success" href="/login/line">LINE LOGIN</a>
                                <a class="btn btn-primary" href="/login/twitter">Twitter Login</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center">
                <a class="btn btn-primary mt-3" href="/register">新規登録はこちらから</a>
            </div>
        </div>
    </div>
</div>
@endsection
