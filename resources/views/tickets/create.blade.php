@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <a href="{{ route('dashboard') }}"
                class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center text-muted hover-primary transition-base">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span class="fw-bold text-uppercase small"
                    style="letter-spacing: 0.1em;">{{ __('Back to Dashboard') }}</span>
            </a>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Ticket') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5"
                                    required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Screenshot (Optional)</label>
                                <div id="drop-zone" class="border rounded p-4 text-center cursor-pointer"
                                    style="border-style: dashed !important; border-width: 2px; background-color: #f8f9fa;">
                                    <div id="drop-zone-text" class="text-muted">
                                        <p class="mb-1">Drag and drop your image here</p>
                                        <p class="small">or click to browse, or paste (Ctrl+V)</p>
                                    </div>
                                    <div id="file-preview-container" class="d-none mt-2">
                                        <img id="image-preview" src="#" alt="Preview" class="img-fluid rounded border"
                                            style="max-height: 200px;">
                                        <p id="file-name" class="mt-1 small text-muted"></p>
                                        <button type="button" id="remove-file-btn"
                                            class="btn btn-sm btn-outline-danger mt-1">Remove</button>
                                    </div>
                                    <input type="file" class="form-control d-none" id="image" name="image" accept="image/*">
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
                                        if (e.target !== removeBtn) {
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
                                        dropZone.addEventListener(eventName, highlight, false);
                                    });

                                    ['dragleave', 'drop'].forEach(eventName => {
                                        dropZone.addEventListener(eventName, unhighlight, false);
                                    });

                                    function highlight(e) {
                                        dropZone.classList.add('bg-light', 'border-primary');
                                    }

                                    function unhighlight(e) {
                                        dropZone.classList.remove('bg-light', 'border-primary');
                                    }

                                    dropZone.addEventListener('drop', handleDrop, false);

                                    function handleDrop(e) {
                                        const dt = e.dataTransfer;
                                        const files = dt.files;
                                        if (files.length) {
                                            handleFiles(files);
                                        }
                                    }

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
                                            alert('Please upload an image file.');
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
                                        e.stopPropagation(); // Prevent opening file dialog
                                        fileInput.value = '';
                                        dropZoneText.classList.remove('d-none');
                                        previewContainer.classList.add('d-none');
                                    });
                                });
                            </script>

                            <button type="submit"
                                class="btn btn-primary col-12 col-sm-auto py-2 fw-bold shadow-sm">{{ __('Submit Ticket') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection