
@extends('layouts.app')

@section('title', 'Search Users')

@section('content')

<div class="row">
    <div class="col-md-12 mb-4">
        <h1>Search Results for "{{ $query }}"</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        @forelse ($users as $user)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="{{ $user->avatar_url ?? 'https://via.placeholder.com/50' }}" alt="{{ $user->name }}" class="rounded-circle me-3" width="50" height="50">
                        <div>
                            <h2 class="h5 mb-1">
                                <a href="{{ route('users.show', $user->id) }}" class="text-decoration-none">{{ $user->name }}</a>
                            </h2>
                            <p class="small mb-0">Joined {{ $user->created_at->format('M Y') }} · {{ $user->posts_count }} posts</p>
                        </div>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-primary ms-auto">View Profile</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center">
                    <p class="mb-0">No users found matching "{{ $query }}".</p>
                </div>
            </div>
        @endforelse
        
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection