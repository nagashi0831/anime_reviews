@extends('layouts.front')


@section('title', 'あにれびゅ！')

@section('content')
<div class="container-fluid">
    <img class="hero-img" src="/storage/image/blue sky.jpeg">
</div>
<div class="container">
    @foreach ($posts as $post)
    @if ($post->image_path != null)
       <img class="img-item" src="/storage/image/{{ $post->image_path }}">
    @endif
    @endforeach
</div>
@endsection