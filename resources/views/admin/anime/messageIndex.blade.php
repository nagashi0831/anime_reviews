@extends('layouts.admin')

@section('title', 'チャット相手いちらん')

@section('content')
    <div class="container mt-3">
        <div class="row">
            <h2 class="text-body">チャット</h2>
        </div>
        <div class="row">
            <div class="offset-md-4">
            </div>
            <div class="col-md-8">
                <form action="{{ action('Admin\MessagesController@indexMem') }}" method="get">
                    <div class="form-group row">
                        <label class="col-md-2">
                            名前で検索
                        </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="cond_name" value="{{ $cond_name }}">
                        </div>
                        <div class="col-md-2">
                            {{ csrf_field() }}
                            <input type="submit" class="btn btn-primary" value="検索">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- チャット可能ユーザー一覧 --}}
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th width="30%" class="text-body">名前</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
            
            @foreach ($partnerInfos as $partnerInfo)
                <tbody>
                    <tr>
                        <th><span class="text-body">{{ $partnerInfo->name }}</span></th>
                        <td>
                            <a class="btn btn-primary" href="/admin/anime/message/{{ $partnerInfo->id }}">チャットする</a>
                        </td>
                    </tr>
                    
                </tbody>
            @endforeach
            </table>
        </div>
    </div>