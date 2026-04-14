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
            <div class="col-md-10 col-lg-9">
                <div class="card shadow-sm border-0 mb-5">
                    <div
                        class="card-header py-4 px-4 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <div>
                            <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                style="font-size: 0.7rem; letter-spacing: 0.1em;">Ticket Reference</small>
                            <h4 class="mb-0 fw-bold">#{{ $ticket->id }} - {{ $ticket->title }}</h4>
                        </div>
                        <div class="d-flex">
                            <span
                                class="badge {{ $ticket->status_badge_class }} py-2 px-3">{{ ucfirst($ticket->status) }}</span>
                        </div>
                    </div>

                    <div class="card-body p-4 p-lg-5">
                        <div class="row g-4 mb-4">
                            <div class="col-6 col-md-4">
                                <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                    style="font-size: 0.75rem; letter-spacing: 0.05em;">Category</small>
                                <span class="fs-6 text-dark fw-semibold">{{ $ticket->category->name }}</span>
                            </div>
                            <div class="col-6 col-md-4">
                                <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                    style="font-size: 0.75rem; letter-spacing: 0.05em;">Priority</small>
                                <span class="fs-6 text-dark fw-semibold">{{ ucfirst($ticket->priority) }}</span>
                            </div>
                            <div class="col-12 col-md-4">
                                <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                    style="font-size: 0.75rem; letter-spacing: 0.05em;">Reporter</small>
                                <span class="fs-6 text-dark fw-semibold">{{ $ticket->user->name }}</span>
                            </div>
                        </div>

                        <hr class="opacity-10 my-4">

                        <h5 class="fw-bold mb-3 text-dark">Description</h5>
                        <p class="text-secondary" style="line-height: 1.6;">{{ $ticket->description }}</p>

                        @if($ticket->client_image_path)
                            <div class="mt-4">
                                <small class="text-uppercase text-muted fw-bold d-block mb-2"
                                    style="font-size: 0.75rem; letter-spacing: 0.05em;">Attachment</small>
                                <img src="{{ Storage::url($ticket->client_image_path) }}" alt="Ticket Attachment"
                                    class="img-fluid rounded border" style="max-height: 300px;">
                            </div>
                        @endif

                        @if($ticket->status === 'resolved')
                            <div class="alert alert-success mt-3">
                                <h5>Resolution Details</h5>
                                <p><strong>Problem:</strong> {{ $ticket->resolution_problem_summary }}</p>
                                <p><strong>Steps:</strong> {{ $ticket->resolution_steps }}</p>
                                <p><strong>Resolved At:</strong> {{ $ticket->resolved_at }}</p>
                                @if($ticket->resolution_image_path)
                                    <div>
                                        <strong>{{ __('Resolved By') }}:</strong>
                                        {{ $ticket->resolver ? $ticket->resolver->name : __('Unknown') }}<br>
                                        <strong>{{ __('Proof') }}:</strong><br>
                                        <img src="{{ Storage::url($ticket->resolution_image_path) }}" alt="Resolution Proof"
                                            class="img-fluid" style="max-height: 300px;">
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if(auth()->user()->role == 'admin' || (auth()->user()->role == 'staff' && $ticket->status !== 'resolved' && $ticket->status !== 'closed'))
                            <hr>
                            <div class="d-flex flex-wrap gap-2">
                                @if($ticket->status !== 'resolved' && $ticket->status !== 'closed')
                                    @if($ticket->status !== 'in_progress')
                                        <form action="{{ route('tickets.inProgress', $ticket) }}" method="POST"
                                            class="col-12 col-sm-auto">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100">{{ __('In Progress') }}</button>
                                        </form>
                                    @endif

                                    <a href="{{ route('tickets.resolveForm', $ticket) }}"
                                        class="btn btn-success col-12 col-sm-auto">{{ __('Resolve Ticket') }}</a>

                                    @if($ticket->status !== 'escalated')
                                        <form action="{{ route('tickets.escalate', $ticket) }}" method="POST"
                                            class="col-12 col-sm-auto">
                                            @csrf
                                            <button type="button" class="btn btn-warning w-100 confirm-delete"
                                                data-confirm="Are you sure you want to escalate this ticket?">Escalate</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        @endif

                        @php
                            $isTerminal = in_array($ticket->status, ['resolved', 'closed']);
                            $canUpdateStatus = auth()->user()->role == 'admin' || (auth()->user()->role == 'staff' && !$isTerminal);
                        @endphp

                        @if($canUpdateStatus)
                            <!-- keep simple status update for other statuses like open/in_progress/closed -->
                            <form action="{{ route('tickets.update', $ticket) }}" method="POST"
                                class="row g-3 align-items-center mt-3">
                                @csrf
                                @method('PUT')
                                <div class="col-12 col-sm-auto">
                                    <label class="fw-bold small text-muted text-uppercase mb-0">Update Status:</label>
                                </div>
                                <div class="col-12 col-sm-auto">
                                    <select name="status" class="form-select" style="min-width: 150px;">
                                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In
                                            Progress</option>
                                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                        <option value="escalated" {{ $ticket->status == 'escalated' ? 'selected' : '' }}>Escalated
                                        </option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-auto">
                                    <button type="submit" class="btn btn-secondary w-100">Update</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Comments</div>
                    <div class="card-body">
                        @foreach($ticket->comments as $comment)
                            <div class="mb-3 border-bottom pb-2">
                                <strong>{{ $comment->user->name }}</strong> <small
                                    class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                <p class="mb-0">{{ $comment->content }}</p>
                            </div>
                        @endforeach

                        <form action="{{ route('comments.store', $ticket) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="mb-3">
                                <textarea name="content" class="form-control" placeholder="Add a comment..."
                                    required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Post Comment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection