<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima perferendis consectetur vero alias suscipit sed distinctio earum provident, quidem, dignissimos nemo, officia illo. Et, saepe doloremque nihil tempora magnam porro?
</body>
</html>

@extends('layouts.app')

@section('title', 'Liste des articles')

@section('content')
    <h1>Articles récents</h1>
    
    @foreach($posts as $post)
        <article class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">{{ $post->title }}</h2>
                <p class="text-muted">Publié le {{ $post->created_at->format('d/m/Y') }}</p>
                <p class="card-text">{{ Str::limit($post->content, 200) }}</p>
                <a href="/posts/{{ $post->id }}" class="btn btn-primary">Lire la suite</a>
            </div>
        </article>
    @endforeach
@endsection

