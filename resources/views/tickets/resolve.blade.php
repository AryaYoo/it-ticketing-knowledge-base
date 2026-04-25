@extends('layouts.app')

@section('content')
    <div class="container py-1">
        <div class="mb-4">
            <a href="{{ route('tickets.show', $ticket) }}"
                class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center text-muted hover-primary transition-base">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span class="fw-bold text-uppercase small" style="letter-spacing: 0.1em;">{{ __('Back to Ticket') }}</span>
            </a>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Resolve Ticket #') . $ticket->id }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('tickets.resolve', $ticket) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="resolution_problem_summary" class="form-label">Problem Summary</label>
                                <textarea class="form-control" id="resolution_problem_summary"
                                    name="resolution_problem_summary" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="resolution_steps" class="form-label">Steps Taken</label>
                                <textarea class="form-control" id="resolution_steps" name="resolution_steps" rows="5"
                                    required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="resolution_image" class="form-label">Proof of Resolution (Mandatory)</label>
                                <input type="file" class="form-control" id="resolution_image" name="resolution_image"
                                    accept="image/*" required>
                            </div>

                            <button type="submit" class="btn btn-success">Complete & Resolve</button>
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
