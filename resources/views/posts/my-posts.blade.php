@extends('layouts.app')

@section('title', 'My posts')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="h4 mb-0">My Posts</h1>
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">Create New Post</a>
                </div>
                <div class="card-body">
                    @if($posts->count() > 0)
                        @foreach($posts as $post)
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ $post->user->avatar_url ?? 'https://via.placeholder.com/50' }}" alt="{{ $post->user->name }}" class="rounded-circle me-2" width="40" height="40">
                                        <div>
                                            <a href="{{ route('users.show', $post->user->id) }}" class="text-decoration-none">{{ $post->user->name }}</a>
                                            <div class=" small">{{ $post->created_at->format('M d, Y') }} · {{ $post->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    
                                    <h2 class="card-title h4 mb-3">
                                        <a href="{{ route('posts.show', $post->uuid) }}" class="text-decoration-none">{{ $post->title }}</a>
                                    </h2>
                                    
                                    @if ($post->featured_image_url)
                                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="img-fluid">
                                    @endif
                                    
                                    <div class="post-excerpt mb-3">
                                        {{ Str::limit(strip_tags($post->content), 200) }}
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-hand-thumbs-up"></i> {{ $post->likes_count ?? 0 }}
                                            </span>
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-hand-thumbs-down"></i> {{ $post->dislikes_count ?? 0 }}
                                            </span>
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-chat"></i> {{ $post->comments_count ?? 0 }}
                                            </span>
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-eye"></i> {{ $post->view_count ?? 0 }}
                                            </span>
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('posts.edit', $post->uuid) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('posts.destroy', $post->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="d-flex justify-content-center">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <h3 class="h5  mb-4">You haven't created any posts yet</h3>
                            <a href="{{ route('posts.create') }}" class="btn btn-primary">Create Your First Post</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection