@extends('layouts.app')

@section('content')
<style>
    .login-hero {
        background: linear-gradient(135deg, var(--primary-color) 0%, #00a852 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .login-hero::before {
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

    .login-hero h3 {
        color: #ffffff !important;
    }

    .login-card {
        background: var(--card-bg);
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    }

    [data-theme="dark"] .login-card {
        border-color: rgba(255, 255, 255, 0.05);
    }

    .login-input-group .input-group-text {
        background: var(--bg-color);
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-right: none;
        border-radius: 10px 0 0 10px;
        padding: 0.6rem 0.85rem;
    }

    .login-input-group .form-control {
        background: var(--bg-color);
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-left: none;
        border-radius: 0 10px 10px 0;
        padding: 0.6rem 0.75rem;
        font-size: 0.9rem;
    }

    .login-input-group .form-control:focus {
        box-shadow: none;
        border-color: var(--primary-color);
    }

    .login-input-group .form-control:focus + .input-group-text,
    .login-input-group:focus-within .input-group-text {
        border-color: var(--primary-color);
    }

    .announcement-mini {
        background: var(--card-bg);
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.2s;
    }

    [data-theme="dark"] .announcement-mini {
        border-color: rgba(255, 255, 255, 0.05);
    }

    .announcement-mini:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .forum-bubble {
        background: var(--bg-color);
        border-radius: 12px;
        border-top-left-radius: 0;
        padding: 0.75rem 1rem;
    }

    .forum-avatar-sm {
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.7rem;
    }
</style>

<div class="container py-1">
    <div class="row justify-content-center align-items-start g-4">

        {{-- Left: Public content --}}
        @if(isset($announcements) && $announcements->count() > 0 || isset($recentPosts))
            <div class="col-lg-7">
                {{-- Announcements --}}
                @if(isset($announcements) && $announcements->count() > 0)
                    <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 0.7rem; letter-spacing: 0.08em;">
                        {{ __('Latest Announcements') }}
                    </h6>
                    @foreach($announcements->take(2) as $announcement)
                        <div class="announcement-mini mb-3">
                            <div class="row g-0">
                                @if($announcement->image_path)
                                    <div class="col-md-4">
                                        <img src="{{ Storage::url($announcement->image_path) }}" class="h-100 w-100"
                                            style="object-fit: cover; min-height: 140px;" alt="{{ $announcement->title }}">
                                    </div>
                                @endif
                                <div class="{{ $announcement->image_path ? 'col-md-8' : 'col-12' }}">
                                    <div class="p-3">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="badge bg-primary bg-opacity-10 text-white border-0 rounded-pill px-2" style="font-size: 0.65rem;">
                                                {{ __('News') }}
                                            </span>
                                            <small class="text-muted" style="font-size: 0.7rem;">{{ $announcement->created_at->diffForHumans() }}</small>
                                        </div>
                                        <h6 class="fw-bold mb-1">{{ $announcement->title }}</h6>
                                        <p class="text-muted mb-0" style="font-size: 0.8rem; line-height: 1.5;">
                                            {{ Str::limit($announcement->content, 120) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($announcements->count() > 2)
                        <p class="text-muted text-center small mb-4">{{ __('Login to see more...') }}</p>
                    @endif
                @endif

                {{-- Forum preview --}}
                <h6 class="fw-bold text-muted text-uppercase mb-3 mt-4" style="font-size: 0.7rem; letter-spacing: 0.08em;">
                    {{ __('Recent Forum Activity') }}
                </h6>

                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body p-3">
                        @if(isset($recentPosts) && $recentPosts->count() > 0)
                            <div class="custom-scrollbar" style="max-height: 350px; overflow-y: auto;">
                                @foreach($recentPosts as $post)
                                    <div class="d-flex gap-2 mb-3">
                                        <div class="forum-avatar-sm">
                                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="forum-bubble">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="fw-bold" style="font-size: 0.8rem;">{{ $post->user->name }}</span>
                                                    <span class="badge bg-opacity-10 border-0 rounded-pill px-2 py-1
                                                        {{ $post->user->role == 'admin' ? 'bg-danger text-danger' : ($post->user->role == 'staff' ? 'bg-primary text-primary' : 'bg-secondary text-secondary') }}"
                                                        style="font-size: 0.6rem;">
                                                        {{ strtoupper($post->user->role) }}
                                                    </span>
                                                </div>
                                                <p class="text-muted mb-1" style="font-size: 0.8rem;">{{ Str::limit($post->content, 100) }}</p>
                                                <div class="text-end">
                                                    <small class="text-muted" style="font-size: 0.65rem;">{{ $post->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-chat-dots text-muted opacity-25" style="font-size: 2rem;"></i>
                                <p class="text-muted small mt-2 mb-0">{{ __('Forum is quiet') }}</p>
                            </div>
                        @endif

                        <div class="text-center mt-2 pt-2 border-top">
                            <p class="text-muted mb-0" style="font-size: 0.75rem;">{{ __('Login to participate in the discussion') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Right: Login form --}}
        <div class="{{ isset($announcements) && $announcements->count() > 0 ? 'col-lg-5' : 'col-md-8 col-lg-5' }}">
            <div class="login-card">
                <div class="login-hero mb-0" style="border-radius: 20px 20px 0 0;">
                    <div class="text-center">
                        <img src="{{ asset('assets/img/logo.svg') }}" alt="Logo" style="height: 48px; filter: brightness(0) invert(1);" class="mb-3">
                        <h3 class="fw-bold mb-1">{{ __('Welcome Back') }}</h3>
                        <p class="text-white-50 small mb-0">{{ __('Please enter your credentials to access your account') }}</p>
                    </div>
                </div>

                <div class="p-4">
                    @if(config('app.debug'))
                        <div class="text-muted text-center mb-3" style="font-size: 0.65rem;">
                            <strong>Debug:</strong> IP <code>{{ request()->ip() }}</code>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.04em;">
                                {{ __('Email Address') }}
                            </label>
                            <div class="login-input-group input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                    placeholder="name@example.com">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.04em;">
                                {{ __('Password') }}
                            </label>
                            <div class="login-input-group input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password" required autocomplete="current-password" placeholder="••••••••">
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="remember" style="font-size: 0.8rem;">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-primary fw-bold text-decoration-none" href="{{ route('password.request') }}" style="font-size: 0.8rem;">
                                    {{ __('Forgot Password?') }}
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3">
                            <i class="bi bi-box-arrow-in-right me-1"></i> {{ __('Login') }}
                        </button>

                        @if (Route::has('register'))
                            <div class="text-center">
                                <span class="text-muted" style="font-size: 0.8rem;">{{ __("Don't have an account?") }}</span>
                                <a class="text-primary fw-bold text-decoration-none" href="{{ route('register') }}" style="font-size: 0.8rem;">
                                    {{ __('Register Now') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($ipMapping))
<div class="position-fixed bottom-0 end-0 m-4 z-3 d-flex flex-column align-items-center">
    <form method="POST" action="{{ route('fast-login') }}">
        @csrf
        <button type="submit"
                class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center p-0"
                style="width: 56px; height: 56px; transition: all 0.3s;"
                title="Masuk sebagai {{ $ipMapping->display_name }}">
            <i class="bi bi-box-arrow-in-right fs-5"></i>
        </button>
    </form>
    <div class="bg-white px-3 py-1 rounded-pill shadow-sm mt-2 border fw-bold text-primary" style="font-size: 0.65rem; white-space: nowrap;">
        {{ $ipMapping->display_name }}
    </div>
</div>
@endif
@endsection
