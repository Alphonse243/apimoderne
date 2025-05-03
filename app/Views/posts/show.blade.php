@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <article>
        <h1>{{ $post->title }}</h1>
        <p class="text-muted">
            Publié le {{ $post->created_at->format('d/m/Y') }}
            par {{ $post->author->name }}
        </p>
        
        <div class="content">
            {{ $post->content }}
        </div>
        
        <div class="mt-4">
            <a href="/posts" class="btn btn-secondary">Retour aux articles</a>
        </div>
    </article>

    @if($post->comments->count() > 0)
        <div class="mt-5">
            <h3>Commentaires</h3>
            @foreach($post->comments as $comment)
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text">{{ $comment->content }}</p>
                        <small class="text-muted">Par {{ $comment->author }} le {{ $comment->created_at->format('d/m/Y') }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
