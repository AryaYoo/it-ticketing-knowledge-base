@extends('layouts.app')

@section('content')
<style>
    .settings-card {
        background: var(--card-bg);
        border-radius: 24px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    [data-theme="dark"] .settings-card {
        border-color: rgba(255, 255, 255, 0.05);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .settings-header {
        padding: 2rem;
        background: rgba(var(--primary-rgb), 0.03);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .settings-body {
        padding: 2.5rem;
    }

    .preview-logo {
        max-width: 200px;
        max-height: 200px;
        border-radius: 12px;
        border: 2px dashed rgba(var(--primary-rgb), 0.2);
        padding: 10px;
        margin-bottom: 1rem;
        background: #f8f9fa;
    }

    [data-theme="dark"] .preview-logo {
        background: rgba(255, 255, 255, 0.02);
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 hover-primary transition-base">
                    <i class="bi bi-arrow-left"></i>
                    <span>{{ __('Back to Dashboard') }}</span>
                </a>
            </div>

            <div class="settings-card">
                <div class="settings-header">
                    <h5 class="fw-bold mb-0 d-flex align-items-center gap-2 text-primary">
                        <i class="bi bi-gear-fill"></i>
                        {{ __('Application Settings') }}
                    </h5>
                </div>

                <div class="settings-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted mb-2">
                                <i class="bi bi-image me-1"></i> {{ __('Application Logo') }}
                            </label>
                            
                            <div class="d-flex flex-column align-items-center mb-3">
                                <div class="preview-logo d-flex align-items-center justify-content-center">
                                    @if(isset($settings['app_logo']))
                                        <img src="{{ asset($settings['app_logo']) }}" id="logo-preview" class="img-fluid" alt="App Logo">
                                    @else
                                        <img src="{{ asset('assets/img/logo.svg') }}" id="logo-preview" class="img-fluid" alt="Default Logo">
                                    @endif
                                </div>
                                <p class="small text-muted">{{ __('Current Logo') }}</p>
                            </div>

                            <div class="input-group">
                                <input type="file" name="app_logo" class="form-control" id="logo-input" accept="image/*">
                            </div>
                            <small class="text-muted d-block mt-1">{{ __('Recommended size: 512x512px. Max: 2MB.') }}</small>
                        </div>

                        <div class="mb-4">
                            <label for="app_name" class="form-label fw-bold small text-uppercase text-muted mb-2">
                                <i class="bi bi-fonts me-1"></i> {{ __('Application Name') }}
                            </label>
                            <input type="text" name="app_name" id="app_name" class="form-control" 
                                value="{{ $settings['app_name'] ?? config('app.name') }}" placeholder="MasTolongMas">
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary py-3 fw-bold shadow-sm rounded-pill">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ __('Save Settings') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('logo-input').onchange = evt => {
        const [file] = evt.target.files
        if (file) {
            document.getElementById('logo-preview').src = URL.createObjectURL(file)
        }
    }
</script>
@endsection
