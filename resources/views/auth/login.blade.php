@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center align-items-start g-5">
            @if(isset($announcements) && $announcements->count() > 0 || isset($recentPosts))
                <div class="col-lg-7">
                    <div class="pe-lg-4">
                        @if(isset($announcements) && $announcements->count() > 0)
                            <h5 class="fw-bold mb-3 text-muted text-uppercase small" style="letter-spacing: 0.1em;">
                                {{ __('Latest Announcements') }}
                            </h5>

                            <div class="announcements-container mb-5">
                                @foreach($announcements->take(2) as $announcement)
                                    <div class="card border-0 shadow-sm mb-4 overflow-hidden transition-hover"
                                        style="border-radius: 16px;">
                                        <div class="row g-0">
                                            @if($announcement->image_path)
                                                <div class="col-md-4">
                                                    <img src="{{ Storage::url($announcement->image_path) }}" class="h-100 w-100"
                                                        style="object-fit: cover; min-height: 150px;" alt="{{ $announcement->title }}">
                                                </div>
                                            @endif
                                            <div class="{{ $announcement->image_path ? 'col-md-8' : 'col-12' }}">
                                                <div class="card-body p-4">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <span
                                                            class="badge bg-primary bg-opacity-10 text-white border-0 rounded-pill px-3">
                                                            {{ __('News') }}
                                                        </span>
                                                        <small
                                                            class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <h4 class="fw-bold mb-2">{{ $announcement->title }}</h4>
                                                    <p class="text-secondary small mb-0 line-clamp-3">
                                                        {{ $announcement->content }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if($announcements->count() > 2)
                                    <div class="text-center">
                                        <p class="text-muted small italic">Login to see more announcements in the forum...</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <h5 class="fw-bold mb-3 text-muted text-uppercase small" style="letter-spacing: 0.1em;">
                            {{ __('Recent Forum Activity') }}
                        </h5>

                        <div class="card border-0 shadow-sm overflow-hidden mb-4"
                            style="border-radius: 20px; background: rgba(var(--bs-light-rgb), 0.5);">
                            <div class="card-body p-4">
                                @if(isset($recentPosts) && $recentPosts->count() > 0)
                                    <div class="forum-feed custom-scrollbar" style="max-height: 400px; overflow-y: auto;">
                                        @foreach($recentPosts as $post)
                                            <div class="d-flex gap-3 mb-4 last-child-mb-0">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                                        style="width: 40px; height: 40px; border-radius: 50%; font-size: 14px;">
                                                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="p-3 rounded-4 shadow-sm"
                                                        style="background: var(--bs-body-bg); border-top-left-radius: 0 !important;">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <span class="fw-bold small">{{ $post->user->name }}</span>
                                                            <span
                                                                class="badge bg-opacity-10 
                                                                                                                                {{ $post->user->role == 'admin' ? 'bg-danger text-danger' : ($post->user->role == 'staff' ? 'bg-primary text-primary' : 'bg-secondary text-secondary') }} 
                                                                                                                                border-0 small rounded-pill px-2 py-1"
                                                                style="font-size: 10px;">
                                                                {{ strtoupper($post->user->role) }}
                                                            </span>
                                                        </div>
                                                        <p class="small mb-1 text-secondary">{{ $post->content }}</p>
                                                        <div class="text-end">
                                                            <small class="text-muted"
                                                                style="font-size: 10px;">{{ $post->created_at->diffForHumans() }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                                class="bi bi-chat-dots text-muted opacity-25" viewBox="0 0 16 16">
                                                <path
                                                    d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                                                <path
                                                    d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.82 2.144l-.252.657zm4.754-1.844c-.535.298-1.742.67-3.345.925a10.7 10.7 0 0 0 1.235-2.223.235.235 0 0 0-.022-.214A8 8 0 0 1 2 8c0-3.314 3.134-6 7-6s7 2.686 7 6-3.134 6-7 6a8 8 0 0 1-2.081-.273.245.245 0 0 0-.202.047z" />
                                            </svg>
                                        </div>
                                        <h6 class="text-muted fw-normal">{{ __('forum sedang sepi') }}</h6>
                                    </div>
                                @endif

                                <div class="mt-3 text-center">
                                    <p class="text-muted small mb-0">{{ __('Login to participate in the discussion') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="{{ isset($announcements) && $announcements->count() > 0 ? 'col-lg-5' : 'col-md-8 col-lg-5' }}">
                <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-5">
                            <img src="{{ asset('assets/img/logo.svg') }}" alt="Logo" class="mb-4" style="height: 60px;">
                            <h3 class="fw-bold mb-1">{{ __('Welcome Back') }}</h3>
                            <p class="text-muted small">{{ __('Please enter your credentials to access your account') }}</p>
                            @if(config('app.debug'))
                                <div class="mt-2 text-muted" style="font-size: 0.7rem;">
                                    <strong>Debug Info:</strong> Detected IP: <code>{{ request()->ip() }}</code>
                                </div>
                            @endif
                        </div>

                        @if(isset($ipMapping))
                            {{-- Floating IP-based login removed from here --}}
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold small text-muted text-uppercase"
                                    style="letter-spacing: 0.05em;">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 px-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="text-muted">
                                            <path
                                                d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                            </path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                    </span>
                                    <input id="email" type="email"
                                        class="form-control bg-light border-0 ps-2 @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                        placeholder="name@example.com">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold small text-muted text-uppercase"
                                    style="letter-spacing: 0.05em;">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 px-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="text-muted">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    </span>
                                    <input id="password" type="password"
                                        class="form-control bg-light border-0 ps-2 @error('password') is-invalid @enderror"
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
                                    <input class="form-check-input mt-1" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label small text-muted" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="small text-primary fw-bold text-decoration-none"
                                        href="{{ route('password.request') }}">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary py-3 fw-bold shadow-sm transition-base">
                                    {{ __('Login') }}
                                </button>
                            </div>

                            @if (Route::has('register'))
                                <div class="text-center">
                                    <span class="text-muted small">{{ __("Don't have an account?") }}</span>
                                    <a class="small text-primary fw-bold text-decoration-none" href="{{ route('register') }}">
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
                    style="width: 65px; height: 65px; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);"
                    onmouseover="this.style.transform='scale(1.1) rotate(5deg)'; this.style.boxShadow='0 1rem 3rem rgba(0,0,0,.175)';"
                    onmouseout="this.style.transform='scale(1) rotate(0deg)'; this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)';"
                    title="Masuk sebagai {{ $ipMapping->display_name }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    <polyline points="10 17 15 12 10 7"></polyline>
                    <line x1="15" y1="12" x2="3" y2="12"></line>
                </svg>
            </button>
        </form>
        <div class="bg-white px-3 py-1 rounded-pill shadow-sm mt-2 border small fw-bold text-primary" style="font-size: 11px; white-space: nowrap;">
            {{ $ipMapping->display_name }}
        </div>
    </div>
    @endif
@endsection