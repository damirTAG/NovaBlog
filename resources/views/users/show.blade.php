@extends('layouts.app')

@section('title', $user->name . '\'s Profile')

@section('content')

    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-gradient-primary text-center p-4 position-relative">
                    <div class="avatar-wrapper position-relative mb-3 mx-auto">
                        <img src="{{ $user->avatar_url ?? 'https://via.placeholder.com/150' }}" 
                            alt="{{ $user->name }}" 
                            class="avatar-img border border-3 border-white shadow">
                    </div>
                    <h2 class="h4 mb-1 text-white">{{ $user->name }}</h2>
                    
                    @if ($user->profile && $user->profile->location)
                        <p class="text-white-50 mb-0">
                            <i class="bi bi-geo-alt"></i> {{ $user->profile->location }}
                        </p>
                    @endif
                    
                    @if (Auth::id() === $user->id)
                        <div class="profile-edit-btn position-absolute top-0 end-0 m-3">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-light" 
                               data-bs-toggle="tooltip" title="Edit Profile">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    @endif
                </div>
                
                <div class="card-body p-4" style="color: #e0e0e0;">
                    @if ($user->profile)
                        @if ($user->profile->bio)
                            <div class="mb-4">
                                <h6 class=" text-uppercase small mb-2">About</h6>
                                <p class="mb-0">{{ $user->profile->bio }}</p>
                            </div>
                        @endif
                        
                        @if ($user->profile->website)
                            <div class="mb-4">
                                <h6 class=" text-uppercase small mb-2">Website</h6>
                                <a href="{{ $user->profile->website }}" target="_blank" rel="noopener noreferrer" 
                                   class="d-flex align-items-center text-decoration-none">
                                    <i class="bi bi-link-45deg me-2"></i>
                                    <span class="text-truncate">{{ str_replace(['https://', 'http://'], '', $user->profile->website) }}</span>
                                </a>
                            </div>
                        @endif
                    @endif
                    
                    <div class="mb-4">
                        <h6 class=" text-uppercase small mb-3">Stats</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-2 d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 32px; height: 32px;">
                                    <i class="bi bi-file-text text-primary"></i>
                                </div>
                                <span>Posts</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $user->posts_count }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-2 d-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10" style="width: 32px; height: 32px;">
                                    <i class="bi bi-hand-thumbs-up text-success"></i>
                                </div>
                                <span>Likes</span>
                            </div>
                            <span class="badge bg-success rounded-pill">{{ $user->likes_received_count }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-2 d-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10" style="width: 32px; height: 32px;">
                                    <i class="bi bi-chat-text text-info"></i>
                                </div>
                                <span>Comments</span>
                            </div>
                            <span class="badge bg-info rounded-pill">{{ $user->comments_received_count }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-0">
                        <h6 class=" text-uppercase small mb-2">Joined</h6>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-event me-2"></i>
                            <span>{{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Posts Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-transparent border-bottom-0 d-flex justify-content-between align-items-center p-4" style="color: #e0e0e0">
                    <h3 class="h5 mb-0">Posts by {{ $user->name }}</h3>
                    <span class="badge bg-primary">{{ $posts->total() }}</span>
                </div>
                
                <div class="card-body pt-0 pb-2 px-4">
                    @forelse ($posts as $post)
                        <div class="post-card mb-4" style="color: #e0e0e0">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h4 class="card-title h5 mb-0">
                                    <a href="{{ route('posts.show', $post->uuid) }}" class="text-decoration-none stretched-link">
                                        {{ $post->title }}
                                    </a>
                                </h4>
                                <span class=" small">{{ $post->created_at->format('M d, Y') }}</span>
                            </div>
                            
                            @if ($post->featured_image_url)
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" 
                                     class="img-fluid rounded mb-3" style="max-height: 200px; object-fit: cover; width: 100%;">
                            @endif
                            
                            <p class=" mb-3">{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center  small">
                                    <div class="me-3">
                                        <i class="bi bi-hand-thumbs-up me-1"></i>
                                        <span>{{ $post->likes_count }}</span>
                                    </div>
                                    <div class="me-3">
                                        <i class="bi bi-chat-text me-1"></i>
                                        <span>{{ $post->comments_count }}</span>
                                    </div>
                                    <div>
                                        <i class="bi bi-eye me-1"></i>
                                        <span>{{ $post->view_count ?? 0 }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('posts.show', $post->uuid) }}" class="btn btn-sm btn-primary">
                                    Read More
                                </a>
                            </div>
                            
                            @if(!$loop->last)
                                <hr class="my-4">
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-file-earmark-text " style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="">No Posts Yet</h5>
                            <p class=" mb-4">{{ $user->name }} hasn't created any posts yet.</p>
                            
                            @if (Auth::id() === $user->id)
                                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Create Your First Post
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>
                
                @if ($posts->count() > 0)
                    <div class="card-footer bg-transparent pt-0 pb-4 px-4">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(to right, var(--primary-color), var(--accent-color));
    }
    
    .card {
        background-color: var(--card-bg);
        border-color: var(--border-color);
    }
    
    .post-card {
        position: relative;
    }
    
    .avatar-wrapper {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
    }
    
    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    @media (max-width: 767.98px) {
        .avatar-wrapper {
            width: 100px;
            height: 100px;
        }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        }
    });
</script>
@endpush
@endsection