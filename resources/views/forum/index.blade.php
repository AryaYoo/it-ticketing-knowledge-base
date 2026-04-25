@extends('layouts.app')

@section('content')
<style>
    .forum-hero {
        background: linear-gradient(135deg, var(--primary-color) 0%, #00a852 100%);
        border-radius: 20px;
        padding: 1.75rem 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .forum-hero::before {
        content: '';
        position: absolute;
        top: -60%;
        right: -8%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        pointer-events: none;
    }

    .forum-hero h1 {
        font-size: 1.5rem;
        color: #ffffff !important;
    }

    .post-card {
        background: var(--card-bg);
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.2s;
    }

    [data-theme="dark"] .post-card {
        border-color: rgba(255, 255, 255, 0.05);
    }

    .post-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .post-avatar {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 50%;
        background: rgba(var(--primary-rgb), 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .compose-card {
        background: var(--card-bg);
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 16px;
        padding: 1.25rem;
    }

    [data-theme="dark"] .compose-card {
        border-color: rgba(255, 255, 255, 0.05);
    }

    .compose-card textarea {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        resize: none;
    }

    .announcement-card {
        background: var(--card-bg);
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.2s;
    }

    [data-theme="dark"] .announcement-card {
        border-color: rgba(255, 255, 255, 0.05);
    }

    .announcement-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .like-btn {
        border: none;
        background: none;
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        font-size: 0.85rem;
        transition: all 0.15s;
    }

    .like-btn:hover {
        background: rgba(220, 53, 69, 0.08);
    }

    .like-btn.liked {
        color: #dc3545;
        background: rgba(220, 53, 69, 0.08);
    }

    .search-box {
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .search-box .form-control {
        padding-left: 2.5rem;
        border-radius: 10px;
        background: var(--bg-color);
        border: 1px solid rgba(0, 0, 0, 0.08);
        font-size: 0.875rem;
    }
</style>

<div class="container py-1">

    {{-- Hero --}}
    <div class="forum-hero">
        <h1 class="fw-bold mb-1">{{ __('Community Forum') }}</h1>
        <p class="text-white-50 mb-0 small">{{ __('Join the conversation with other users and staff.') }}</p>
    </div>

    {{-- Announcements --}}
    @if($announcements->count() > 0)
        <div class="mb-4">
            <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 0.7rem; letter-spacing: 0.08em;">
                {{ __('Announcements') }}
            </h6>
            @foreach($announcements as $announcement)
                <div class="announcement-card mb-3">
                    <div class="row g-0">
                        @if($announcement->image_path)
                            <div class="col-12 col-md-4">
                                <img src="{{ Storage::url($announcement->image_path) }}" class="img-fluid h-100 w-100"
                                    style="object-fit: cover; min-height: 200px;" alt="{{ $announcement->title }}">
                            </div>
                        @endif
                        <div class="{{ $announcement->image_path ? 'col-12 col-md-8' : 'col-12' }}">
                            <div class="p-4">
                                <h5 class="fw-bold mb-2">{{ $announcement->title }}</h5>
                                <p class="text-muted small mb-3" style="line-height: 1.6;">{{ $announcement->content }}</p>
                                <div class="d-flex align-items-center gap-3 text-muted" style="font-size: 0.75rem;">
                                    <span><i class="bi bi-clock me-1"></i>{{ $announcement->created_at->diffForHumans() }}</span>
                                    <span><i class="bi bi-person me-1"></i>{{ $announcement->user->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="row g-4">
        {{-- Left: Compose --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 80px; z-index: 10;">
                <div class="compose-card">
                    <h6 class="fw-bold mb-3">{{ __("What's on your mind?") }}</h6>
                    <form action="{{ route('forum.store') }}" method="POST">
                        @csrf
                        <textarea class="form-control mb-3" name="content" rows="4"
                            placeholder="Type your message here..."></textarea>
                        <button type="submit" class="btn btn-primary w-100 fw-bold btn-sm py-2">
                            <i class="bi bi-send me-1"></i> {{ __('Post Message') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right: Posts --}}
        <div class="col-lg-8">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <h6 class="fw-bold text-muted text-uppercase mb-0" style="font-size: 0.7rem; letter-spacing: 0.08em;">
                    {{ __('Recent Discussions') }}
                </h6>
                <form action="{{ route('forum.index') }}" method="GET" class="search-box" style="min-width: 220px;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('Search posts...') }}" value="{{ $search ?? '' }}">
                </form>
            </div>

            @forelse($posts as $post)
                <div class="post-card">
                    <div class="d-flex gap-3 mb-3">
                        <div class="post-avatar text-primary">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-0">
                                <span class="fw-bold small">{{ $post->user->name }}</span>
                                @if($post->user->role == 'admin')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border-0" style="font-size: 0.65rem;">Admin</span>
                                @elseif($post->user->role == 'staff')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border-0" style="font-size: 0.65rem;">Staff</span>
                                @endif
                                <span class="text-muted" style="font-size: 0.7rem;">· {{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="mb-3 small" style="line-height: 1.6;">{{ $post->content }}</p>

                    <div class="d-flex align-items-center">
                        <form action="{{ route('forum.react', $post) }}" method="POST">
                            @csrf
                            @php
                                $liked = $post->reactions->contains('user_id', auth()->id());
                                $count = $post->reactions->count();
                            @endphp
                            <button type="submit" class="like-btn d-flex align-items-center gap-1 {{ $liked ? 'liked' : 'text-muted' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                                <span class="fw-bold" style="font-size: 0.8rem;">{{ $count }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-chat-dots text-muted opacity-25" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3 mb-0">{{ __('No discussions yet. Be the first to start one!') }}</p>
                </div>
            @endforelse

            @if($posts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
