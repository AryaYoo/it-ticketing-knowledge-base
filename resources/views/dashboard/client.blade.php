@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Action Section -->
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 gap-4 text-center text-md-start">
                    <div>
                        <h1 class="fw-bold mb-1">{{ __('Hello') }}, {{ auth()->user()->name }}!</h1>
                        <p class="text-muted mb-0">Need help? We're here for you.</p>
                    </div>

                    <div class="create-ticket-wrapper">
                        <style>
                            .create-ticket-btn {
                                width: 160px;
                                height: 160px;
                                border: 4px solid rgba(255, 255, 255, 0.2);
                            }

                            @media (max-width: 768px) {
                                .create-ticket-btn {
                                    width: 120px;
                                    height: 120px;
                                    border-width: 3px;
                                }

                                .create-ticket-btn svg {
                                    width: 32px;
                                    height: 32px;
                                }

                                .create-ticket-btn span {
                                    font-size: 0.7rem !important;
                                }
                            }
                        </style>
                        <a href="{{ route('tickets.create') }}"
                            class="btn btn-primary rounded-circle shadow-lg d-flex flex-column align-items-center justify-content-center transition-base hover-lift text-decoration-none create-ticket-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                class="mb-2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span class="fw-bold text-uppercase small text-center px-3"
                                style="letter-spacing: 0.1em; line-height: 1.2;">
                                {{ __('Create New Ticket') }}
                            </span>
                        </a>
                    </div>
                </div>

                @php
                    $latestTicket = $tickets->first();
                    $isRecent = false;
                    if ($latestTicket) {
                        // Consider recent if created in the last 5 minutes AND not resolved
                        $createdAt = $latestTicket->created_at;
                        $isRecent = $createdAt->diffInMinutes(now()) < 5 && $latestTicket->status !== 'resolved';
                    }
                @endphp

                @if($latestTicket && $isRecent)
                    <div class="card border-0 shadow-sm bg-primary text-white mb-5 overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="text-white-50 text-uppercase fw-bold mb-3" style="letter-spacing: 0.1em;">
                                        {{ __('Latest Submission') }}
                                    </h6>
                                    <h2 class="fw-bold mb-3">{{ $latestTicket->title }}</h2>
                                    <div class="d-flex gap-4 mb-4">
                                        <div>
                                            <small class="text-white-50 d-block">{{ __('Status') }}</small>
                                            <span class="fw-bold fs-5"
                                                id="ticket-status-text">{{ __(ucfirst($latestTicket->status)) }}</span>
                                        </div>
                                        <div class="vr opacity-25"></div>
                                        <div>
                                            <small class="text-white-50 d-block">Created At</small>
                                            <span class="fw-bold fs-5">{{ $latestTicket->created_at->format('H:i:s') }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('tickets.show', $latestTicket) }}"
                                        class="btn btn-light text-primary fw-bold px-4">{{ __('View Details') }}</a>
                                </div>
                                <div class="col-md-4 text-center mt-4 mt-md-0">
                                    <div class="bg-white bg-opacity-10 rounded-4 p-4">
                                        <h6 class="text-white-50 text-uppercase fw-bold mb-2">{{ __('Estimated Response') }}
                                        </h6>
                                        <div id="countdown-timer" class="display-4 fw-bold mb-0">--:--</div>
                                        <p class="small text-white-50 mb-0 mt-2" id="countdown-label">
                                            {{ __('Waiting for staff...') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        (function () {
                            const createdAt = new Date("{{ $latestTicket->created_at->toIso8601String() }}").getTime();
                            const fiveMinutes = 5 * 60 * 1000;
                            const targetTime = createdAt + fiveMinutes;
                            const ticketId = {{ $latestTicket->id }};
                            const status = "{{ $latestTicket->status }}";

                            function updateCountdown() {
                                const now = new Date().getTime();
                                const distance = targetTime - now;

                                const timerElement = document.getElementById('countdown-timer');
                                const labelElement = document.getElementById('countdown-label');
                                const statusElement = document.getElementById('ticket-status-text');

                                if (status !== 'open') {
                                    timerElement.innerText = "{{ __('Resolved') }}";
                                    timerElement.classList.add('fs-1');
                                    labelElement.innerText = "{{ __('Ticket telah diterima') }}";
                                    return; // Stop if accepted
                                }

                                if (distance < 0) {
                                    timerElement.innerText = "00:00";
                                    labelElement.innerText = "Processing...";
                                    return;
                                }

                                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                timerElement.innerText =
                                    (minutes < 10 ? "0" : "") + minutes + ":" +
                                    (seconds < 10 ? "0" : "") + seconds;
                            }

                            updateCountdown();
                            const interval = setInterval(updateCountdown, 1000);
                        })();
                    </script>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-header py-4 px-4 border-bottom">
                        <h4 class="mb-0 fw-bold">{{ __('Ticket History') }}</h4>
                    </div>

                    <div class="card-body p-0">
                        <div class="px-4 py-3">
                            <form action="{{ route('dashboard') }}" method="GET" class="d-flex gap-2"
                                style="max-width: 400px;">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-2 border-end-0 pe-0">

                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search"
                                        class="form-control border-2 border-start-0 bg-white ps-2"
                                        placeholder="{{ __('Search ticket title...') }}" value="{{ $search ?? '' }}">
                                </div>
                                <button type="submit" class="btn btn-primary px-3 fw-bold">{{ __('Search') }}</button>
                                @if($search ?? false)
                                    <a href="{{ route('dashboard') }}"
                                        class="btn btn-outline-secondary px-3 fw-bold">{{ __('Clear') }}</a>
                                @endif
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">{{ __('ID') }}</th>
                                        <th class="py-3">{{ __('Title') }}</th>
                                        <th class="py-3">{{ __('Status') }}</th>
                                        <th class="py-3">{{ __('Priority') }}</th>
                                        <th class="text-end pe-4 py-3">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets as $ticket)
                                        <tr>
                                            <td class="ps-4"><span class="text-muted fw-bold">#{{ $ticket->id }}</span></td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $ticket->title }}</div>
                                                <small class="text-muted">{{ $ticket->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge {{ $ticket->status_badge_class }}">
                                                    {{ __(ucfirst($ticket->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $priorityClass = 'bg-info';
                                                    if ($ticket->priority == 'high')
                                                        $priorityClass = 'bg-danger';
                                                    if ($ticket->priority == 'medium')
                                                        $priorityClass = 'bg-warning';
                                                @endphp
                                                <span
                                                    class="badge {{ $priorityClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $priorityClass) }} border-0 shadow-none">
                                                    {{ __(ucfirst($ticket->priority)) }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('tickets.show', $ticket) }}"
                                                    class="btn btn-outline-primary btn-sm px-3 fw-bold">{{ __('View') }}</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted py-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="mb-3 opacity-25 text-primary">
                                                        <path
                                                            d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z">
                                                        </path>
                                                        <polyline points="14 2 14 8 20 8"></polyline>
                                                    </svg>
                                                    <p class="fs-5 mb-0">No tickets found.</p>
                                                    <a href="{{ route('tickets.create') }}"
                                                        class="btn btn-link link-primary">Create your first ticket</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($tickets->hasPages())
                            <div class="card-footer bg-white border-top py-3">
                                {{ $tickets->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection