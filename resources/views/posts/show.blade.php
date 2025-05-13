@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3" style="display: flex; align-items: center;">
                    <img src="{{ $post->user->avatar_url ?? 'https://via.placeholder.com/50' }}" alt="{{ $post->user->name }}" class="rounded-circle me-2" width="40" height="40">
                    <div style='margin-left: 10px'>
                        <a href="{{ route('users.show', $post->user->id) }}" class="text-decoration-none">{{ $post->user->name }}</a>
                        <div class="small">{{ $post->created_at->format('M d, Y') }} · {{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                
                <h1 class="card-title h2 mb-3">{{ $post->title }}</h1>
                
                @if ($post->featured_image_url)
                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="img-fluid rounded mb-4">
                @endif
                
                <div class="post-content mb-4">
                    {!! $htmlContent !!}
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex" id="like-buttons-{{ $post->uuid }}">
                            <button onclick="toggleLike('{{ $post->uuid }}', 1)" class="btn btn-sm {{ $userLike && $userLike->is_like ? 'btn-primary' : 'btn-outline-primary' }} me-2" id="like-btn-{{ $post->uuid }}">
                                <i class="bi bi-hand-thumbs-up"></i> Like (<span id="likes-count-{{ $post->uuid }}">{{ $post->likes_count }}</span>)
                            </button>

                            <button onclick="toggleLike('{{ $post->uuid }}', 0)" class="btn btn-sm {{ $userLike && !$userLike->is_like ? 'btn-danger' : 'btn-outline-danger' }}" id="dislike-btn-{{ $post->uuid }}">
                                <i class="bi bi-hand-thumbs-down"></i> Dislike (<span id="dislikes-count-{{ $post->uuid }}">{{ $post->dislikes_count }}</span>)
                            </button>
                        </div>
                    
                    <div class="small text-muted">
                        <i class="bi bi-eye"></i> {{ $post->view_count }} views
                    </div>
                </div>
                
                @can('update', $post)
                    <div class="d-flex justify-content-end mb-4">
                        <a href="{{ route('posts.edit', $post->uuid) }}" class="btn btn-sm btn-primary me-2">Edit</a>
                        <form action="{{ route('posts.destroy', $post->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                @endcan
                
                <hr>
                
                <h3 class="h5 mb-4">Comments ({{ $post->comments_count }})</h3>
                
                @auth
                    <div class="mb-4">
                        <form action="{{ route('comments.store', $post->uuid) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control" name="content" rows="3" placeholder="Write a comment..." required></textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Post Comment</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="alert alert-info mb-4">
                        <a href="{{ route('login') }}">Login</a> to leave a comment.
                    </div>
                @endauth
                
                <div class="comments">
                    @forelse ($comments as $comment)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex mb-2">
                                    <img src="{{ $comment->user->avatar_url ?? 'https://via.placeholder.com/40' }}" alt="{{ $comment->user->name }}" class="rounded-circle me-2" width="32" height="32">
                                    <div>
                                        <a href="{{ route('users.show', $comment->user->id) }}" class="text-decoration-none">{{ $comment->user->name }}</a>
                                        <div class="small">{{ $comment->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                
                                <p class="mb-2">{{ $comment->content }}</p>
                                
                                @can('update', $comment)
                                    <div class="d-flex justify-content-end">
                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="mb-0">No comments yet. Be the first to comment!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection