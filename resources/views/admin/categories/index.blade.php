@extends('layouts.app')

@section('content')
    <div class="container py-1">
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Category Management') }}</span>
                        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">Add New Category</a>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <form action="{{ route('categories.index') }}" method="GET" class="d-flex gap-2"
                                style="max-width: 400px;">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-2 border-end-0 pe-0">

                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search"
                                        class="form-control border-2 border-start-0 bg-white ps-2"
                                        placeholder="{{ __('Search category name...') }}" value="{{ $search ?? '' }}">
                                </div>
                                <button type="submit" class="btn btn-primary px-3 fw-bold">{{ __('Search') }}</button>
                                @if($search ?? false)
                                    <a href="{{ route('categories.index') }}"
                                        class="btn btn-outline-secondary px-3 fw-bold">{{ __('Clear') }}</a>
                                @endif
                            </form>
                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            @if($category->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('categories.edit', $category->id) }}"
                                                    class="btn btn-sm btn-info text-white">Edit</a>
                                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger confirm-delete"
                                                        data-confirm="Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini mungkin mempengaruhi tiket yang ada.">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
