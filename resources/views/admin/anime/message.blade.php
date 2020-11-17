@extends('layouts.admin')

@section('title', 'チャット画面')

@section('content')
    <div class="container">
        {{-- チャットルーム --}}
        <div class="room mt-5 pt-3">
            @foreach($messages as $message)
                {{--　送信したメッセージ --}}
                @if ($message->send == $ToRe['send'])
                    <div class="send text-right text-body">
                        <p>{{ $message->message }}</p>
                    </div>
                @endif
                
                {{-- 受信したメッセージ --}}
                @if($message->receive == $ToRe['send'])
                    <div class="receive text-left text-body">
                        <p>{{ $message->message }}</p>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="sendForm fixed-bottom text-center">
            <form method="POST" action="{{ url('/admin/anime/message/send') }}">
                @csrf
                <input type="hidden" name="send" value="{{ $ToRe['send'] }}">
                <input type="hidden" name="receive" value="{{ $ToRe['receive'] }}">
                <textarea name="message" style="width:80%"></textarea>
                <input type="submit" class="btn btn-primary" value="送信">
            </form>
        </div>
    </div>