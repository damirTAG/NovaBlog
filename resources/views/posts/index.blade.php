@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1>Latest Posts</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        @forelse ($posts as $post)
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $post->user->avatar_url ?? 'https://via.placeholder.com/50' }}" alt="{{ $post->user->name }}" class="rounded-circle me-2" width="40" height="40">
                        <div style='margin-left: 10px'>
                            <a href="{{ route('users.show', $post->user->id) }}" class="text-decoration-none">{{ $post->user->name }}</a>
                            <div class=" small">{{ $post->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    
                    <h2 class="card-title h4">
                        <a href="{{ route('posts.show', $post->uuid) }}" class="text-decoration-none">{{ $post->title }}</a>
                    </h2>
                    
                    @if ($post->featured_image_url)
                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="img-fluid rounded mb-3">
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small">
                            <span><i class="bi bi-hand-thumbs-up"></i> {{ $post->likes_count }}</span>
                            <span class="ms-2"><i class="bi bi-hand-thumbs-down"></i> {{ $post->dislikes_count }}</span>
                            <span class="ms-2"><i class="bi bi-chat"></i> {{ $post->comments_count }}</span>
                            <span class="ms-2"><i class="bi bi-eye"></i> {{ $post->view_count }}</span>
                        </div>
                        <a href="{{ route('posts.show', $post->uuid) }}" class="btn btn-sm btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center">
                    <p class="mb-0">No posts found. Be the first to create a post!</p>
                    @auth
                        <a href="{{ route('posts.create') }}" class="btn btn-primary mt-3">Create Post</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary mt-3">Login to Create Post</a>
                    @endauth
                </div>
            </div>
        @endforelse
        
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Welcome to Blog App</h5>
                <p class="card-text">Share your thoughts and ideas with our community. Write articles, engage with others, and discover interesting content.</p>
                @guest
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                    </div>
                @else
                    <div class="d-grid">
                        <a href="{{ route('posts.create') }}" class="btn btn-primary">Create New Post</a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection