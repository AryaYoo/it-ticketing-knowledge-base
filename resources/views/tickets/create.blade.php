@extends('layouts.app')

@section('content')
    <style>
        .form-card {
            background: var(--card-bg);
            border-radius: 24px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-top: -5rem;
            position: relative;
            z-index: 10;
        }

        [data-theme="dark"] .form-card {
            border-color: rgba(255, 255, 255, 0.05);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .form-header {
            padding: 2rem;
            background: rgba(var(--primary-rgb), 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .form-body {
            padding: 2.5rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 1.5px solid rgba(0, 0, 0, 0.08);
            background-color: var(--bg-color);
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.1);
            background-color: var(--card-bg);
        }

        .drop-zone {
            border: 2px dashed rgba(var(--primary-rgb), 0.3);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: rgba(var(--primary-rgb), 0.01);
        }

        .drop-zone:hover {
            border-color: var(--primary-color);
            background: rgba(var(--primary-rgb), 0.04);
        }

        .btn-submit {
            border-radius: 14px;
            padding: 1rem 2rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 10px 20px rgba(var(--primary-rgb), 0.2);
            transition: all 0.3s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(var(--primary-rgb), 0.3);
        }
    </style>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="mb-4">
                    <a href="{{ route('dashboard') }}"
                        class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 hover-primary transition-base">
                        <i class="bi bi-arrow-left"></i>
                        <span>{{ __('Back to Dashboard') }}</span>
                    </a>
                </div>

                <div class="form-card">
                    <div class="form-header">
                        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2 text-primary">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('Ticket Information') }}
                        </h5>
                    </div>

                    <div class="form-body">
                        <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label for="title" class="form-label">
                                    <i class="bi bi-type text-primary"></i>
                                    {{ __('Title') }}
                                </label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="e.g. Printer not working" required>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="category_id" class="form-label">
                                        <i class="bi bi-tag text-primary"></i>
                                        {{ __('Category') }}
                                    </label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="" disabled selected>{{ __('Select Category') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mt-4 mt-md-0">
                                    <label for="priority" class="form-label">
                                        <i class="bi bi-flag text-primary"></i>
                                        {{ __('Priority') }}
                                    </label>
                                    <select class="form-select" id="priority" name="priority" required>
                                        <option value="low">{{ __('Low') }}</option>
                                        <option value="medium" selected>{{ __('Medium') }}</option>
                                        <option value="high">{{ __('High') }}</option>
                                        <option value="critical">{{ __('Critical') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label">
                                    <i class="bi bi-justify-left text-primary"></i>
                                    {{ __('Description') }}
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="5"
                                    placeholder="{{ __('Describe the issue in detail...') }}" required></textarea>
                            </div>

                            <div class="mb-5">
                                <label class="form-label">
                                    <i class="bi bi-image text-primary"></i>
                                    {{ __('Screenshot (Optional)') }}
                                </label>
                                <div id="drop-zone" class="drop-zone">
                                    <div id="drop-zone-text">
                                        <i class="bi bi-cloud-arrow-up text-primary opacity-50 mb-2"
                                            style="font-size: 2.5rem;"></i>
                                        <p class="mb-1 fw-semibold text-dark">{{ __('Drag and drop your image here') }}</p>
                                        <p class="small text-muted">{{ __('or click to browse, or paste (Ctrl+V)') }}</p>
                                    </div>
                                    <div id="file-preview-container" class="d-none mt-2">
                                        <img id="image-preview" src="#" alt="Preview"
                                            class="img-fluid rounded border shadow-sm mb-3" style="max-height: 250px;">
                                        <div class="d-flex align-items-center justify-content-center gap-3">
                                            <span id="file-name" class="small text-muted fw-medium"></span>
                                            <button type="button" id="remove-file-btn"
                                                class="btn btn-sm btn-outline-danger rounded-pill px-3">{{ __('Remove') }}</button>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control d-none" id="image" name="image" accept="image/*">
                                </div>
                            </div>

                            <div class="d-grid pt-2">
                                <button type="submit" class="btn btn-primary btn-submit">
                                    <i class="bi bi-send-fill me-2"></i>
                                    {{ __('Submit Ticket') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('image');
            const dropZoneText = document.getElementById('drop-zone-text');
            const previewContainer = document.getElementById('file-preview-container');
            const imagePreview = document.getElementById('image-preview');
            const fileName = document.getElementById('file-name');
            const removeBtn = document.getElementById('remove-file-btn');

            // Click to browse
            dropZone.addEventListener('click', (e) => {
                if (e.target !== removeBtn && !removeBtn.contains(e.target)) {
                    fileInput.click();
                }
            });

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) {
                    showPreview(fileInput.files[0]);
                }
            });

            // Drag & Drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.style.borderColor = 'var(--primary-color)';
                    dropZone.style.background = 'rgba(var(--primary-rgb), 0.08)';
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.style.borderColor = 'rgba(var(--primary-rgb), 0.3)';
                    dropZone.style.background = 'rgba(var(--primary-rgb), 0.01)';
                }, false);
            });

            dropZone.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                if (files.length) handleFiles(files);
            }, false);

            // Paste
            document.addEventListener('paste', (e) => {
                const items = (e.clipboardData || e.originalEvent.clipboardData).items;
                for (const item of items) {
                    if (item.type.indexOf('image') !== -1) {
                        const blob = item.getAsFile();
                        handleFiles([blob]);
                    }
                }
            });

            function handleFiles(files) {
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                    showPreview(file);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "{{ __('Please upload an image file.') }}",
                    });
                }
            }

            function showPreview(file) {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = function () {
                    imagePreview.src = reader.result;
                    fileName.textContent = file.name || "Pasted Image";
                    dropZoneText.classList.add('d-none');
                    previewContainer.classList.remove('d-none');
                }
            }

            removeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.value = '';
                dropZoneText.classList.remove('d-none');
                previewContainer.classList.add('d-none');
            });
        });
    </script>
@endsection
