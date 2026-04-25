@extends('layouts.app')

@section('content')
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(0, 0, 0, 0.06);
            --premium-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }

        [data-theme="dark"] {
            --glass-bg: rgba(22, 28, 45, 0.7);
            --glass-border: rgba(255, 255, 255, 0.05);
            --premium-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }

        /* ── Hero: compact, not dominant ── */
        .dashboard-hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, #00a852 100%);
            border-radius: 20px;
            padding: 1.75rem 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .dashboard-hero::before {
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

        .dashboard-hero h1 {
            font-size: 1.5rem;
            color: #ffffff !important;
        }

        /* ── Giant circle CTA button ── */
        .create-ticket-btn {
            width: 180px;
            height: 180px;
            border: 4px solid var(--primary-color) !important;
            background: rgba(var(--primary-rgb), 0.06) !important;
            color: var(--primary-color) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
        }

        .create-ticket-btn:hover {
            background: var(--primary-color) !important;
            color: white !important;
            transform: scale(1.06);
            box-shadow: 0 12px 32px rgba(var(--primary-rgb), 0.25) !important;
        }

        .create-ticket-btn:active {
            transform: scale(0.96);
        }

        @media (max-width: 768px) {
            .create-ticket-btn {
                width: 130px;
                height: 130px;
                border-width: 3px !important;
            }

            .create-ticket-btn svg {
                width: 28px;
                height: 28px;
            }

            .create-ticket-btn span {
                font-size: 0.65rem !important;
            }
        }

        /* ── Stat cards ── */
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            transition: all 0.25s ease;
            box-shadow: var(--premium-shadow);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .icon-box {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Table ── */
        .table-container {
            background: var(--card-bg);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--premium-shadow);
            border: 1px solid var(--glass-border);
        }

        .table thead th {
            background: rgba(var(--primary-rgb), 0.03);
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.06em;
            font-weight: 700;
            color: var(--text-muted);
            border-bottom: none;
            padding: 1rem 1rem;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
            vertical-align: middle;
        }

        [data-theme="dark"] .table tbody td {
            border-bottom-color: rgba(255, 255, 255, 0.05);
        }

        /* Column widths */
        .col-id {
            width: 60px;
        }

        .col-title {
            width: auto;
        }

        .col-status {
            width: 120px;
        }

        .col-prio {
            width: 100px;
        }

        .col-action {
            width: 80px;
        }

        /* ── Search & filter bar ── */
        .search-input-wrapper {
            position: relative;
        }

        .search-input-wrapper i {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .search-input-wrapper .form-control {
            padding-left: 2.5rem;
            border-radius: 10px;
            background: var(--bg-color);
            border: 1px solid var(--glass-border);
            font-size: 0.875rem;
        }

        .filter-btn {
            border: 1px solid var(--glass-border);
            background: var(--bg-color);
            color: var(--text-muted);
            border-radius: 8px;
            padding: 0.4rem 0.85rem;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.15s;
            white-space: nowrap;
            text-decoration: none;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* ── Sidebar cards ── */
        .action-card {
            border-radius: 14px;
            transition: all 0.2s;
            border: 1px solid var(--glass-border);
            background: var(--card-bg);
        }

        .action-card:hover {
            border-color: var(--primary-color);
            background: rgba(var(--primary-rgb), 0.03);
        }

        /* ── Empty state ── */
        .empty-state {
            padding: 2.5rem 1rem;
        }

        .empty-state svg {
            color: var(--text-muted);
            opacity: 0.2;
        }

        /* ── Floating btn ── */
        .btn-create-floating {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(var(--primary-rgb), 0.3);
            z-index: 1000;
            transition: all 0.3s;
        }

        .btn-create-floating:hover {
            transform: scale(1.1) rotate(90deg);
        }
    </style>

    <div class="container py-4">

        {{-- ── Row 1: Compact hero + Giant CTA button ── --}}
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 mb-4">
            {{-- Hero greeting --}}
            <div class="dashboard-hero flex-grow-1 w-100">
                <p class="text-white-50 small fw-semibold mb-1">{{ now()->format('l, d F Y') }}</p>
                <h1 class="fw-bold mb-1 text-white">{{ __('Hello') }}, {{ auth()->user()->name }}!</h1>
                <p class="text-white-50 mb-0 small">{{ __('Welcome back. How can we help you today?') }}</p>
            </div>

            {{-- Giant circle CTA button --}}
            <div class="text-center flex-shrink-0">
                <a href="{{ route('tickets.create') }}"
                    class="btn rounded-circle d-flex flex-column align-items-center justify-content-center text-decoration-none create-ticket-btn mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        class="mb-2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span class="fw-bold text-uppercase text-center px-2"
                        style="font-size: 0.65rem; letter-spacing: 0.08em; line-height: 1.2;">
                        {{ __('Create New Ticket') }}
                    </span>
                </a>
            </div>
        </div>

        {{-- ── Row 2: Stats ── --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-ticket-perforated fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('Total Tickets') }}</div>
                        <h3 class="fw-bold mb-0">{{ $totalTickets }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('Active Tickets') }}</div>
                        <h3 class="fw-bold mb-0">{{ $openTickets }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('Resolved Tickets') }}</div>
                        <h3 class="fw-bold mb-0">{{ $resolvedTickets }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Row 3: Recent ticket spotlight ── --}}
        @php
            $latestTicket = $tickets->first();
            $isRecent = false;
            if ($latestTicket) {
                $isRecent = $latestTicket->created_at->diffInMinutes(now()) < 5 && $latestTicket->status !== 'resolved';
            }
        @endphp

        @if($latestTicket && $isRecent)
            <div class="card border-0 shadow-sm bg-primary text-white mb-4 overflow-hidden" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span
                            class="badge bg-white bg-opacity-20 text-white border-0 small">{{ __('Latest Submission') }}</span>
                        <div class="text-white-50 small" id="countdown-label">{{ __('Estimated Response') }}</div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="fw-bold mb-2">{{ $latestTicket->title }}</h5>
                            <div class="d-flex gap-4 mb-3">
                                <div>
                                    <small class="text-white-50 d-block">{{ __('Status') }}</small>
                                    <span class="fw-bold"
                                        id="ticket-status-text">{{ __(ucfirst($latestTicket->status)) }}</span>
                                </div>
                                <div class="vr opacity-25"></div>
                                <div>
                                    <small class="text-white-50 d-block">{{ __('Sent') }}</small>
                                    <span class="fw-bold">{{ $latestTicket->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                            <a href="{{ route('tickets.show', $latestTicket) }}"
                                class="btn btn-light text-primary fw-bold px-4 btn-sm rounded-pill">{{ __('View Details') }}</a>
                        </div>
                        <div class="col-md-4 text-center mt-3 mt-md-0">
                            <div class="display-5 fw-bold" id="countdown-timer">--:--</div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                (function () {
                    const createdAt = new Date("{{ $latestTicket->created_at->toIso8601String() }}").getTime();
                    const fiveMinutes = 5 * 60 * 1000;
                    const targetTime = createdAt + fiveMinutes;
                    const status = "{{ $latestTicket->status }}";

                    function updateCountdown() {
                        const now = new Date().getTime();
                        const distance = targetTime - now;
                        const timerElement = document.getElementById('countdown-timer');
                        const labelElement = document.getElementById('countdown-label');

                        if (status !== 'open') {
                            timerElement.innerText = "✓";
                            labelElement.innerText = "{{ __('Received') }}";
                            return;
                        }

                        if (distance < 0) {
                            timerElement.innerText = "00:00";
                            labelElement.innerText = "Processing...";
                            return;
                        }

                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        timerElement.innerText = (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
                    }

                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                })();
            </script>
        @endif

        {{-- ── Row 4: Table + Sidebar ── --}}
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="table-container">
                    {{-- Header: title + search + filters --}}
                    <div class="px-4 pt-4 pb-3">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                            <h5 class="mb-0 fw-bold">{{ __('Ticket History') }}</h5>
                            <form action="{{ route('dashboard') }}" method="GET" class="search-input-wrapper"
                                style="min-width: 220px;">
                                <i class="bi bi-search"></i>
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="{{ __('Search ticket title...') }}" value="{{ $search ?? '' }}">
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                            </form>
                        </div>
                        {{-- Filter chips --}}
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('dashboard', array_merge(request()->except('status', 'page'), [])) }}"
                                class="filter-btn {{ !request('status') ? 'active' : '' }}">{{ __('All') }}</a>
                            <a href="{{ route('dashboard', array_merge(request()->except('page'), ['status' => 'open'])) }}"
                                class="filter-btn {{ request('status') === 'open' ? 'active' : '' }}">
                                <i class="bi bi-circle-fill text-warning me-1" style="font-size: 0.5rem;"></i> {{ __('Open') }}
                            </a>
                            <a href="{{ route('dashboard', array_merge(request()->except('page'), ['status' => 'in_progress'])) }}"
                                class="filter-btn {{ request('status') === 'in_progress' ? 'active' : '' }}">
                                <i class="bi bi-circle-fill text-info me-1" style="font-size: 0.5rem;"></i> {{ __('In Progress') }}
                            </a>
                            <a href="{{ route('dashboard', array_merge(request()->except('page'), ['status' => 'resolved'])) }}"
                                class="filter-btn {{ request('status') === 'resolved' ? 'active' : '' }}">
                                <i class="bi bi-circle-fill text-success me-1" style="font-size: 0.5rem;"></i> {{ __('Resolved') }}
                            </a>
                            <a href="{{ route('dashboard', array_merge(request()->except('page'), ['status' => 'escalated'])) }}"
                                class="filter-btn {{ request('status') === 'escalated' ? 'active' : '' }}">
                                <i class="bi bi-circle-fill text-danger me-1" style="font-size: 0.5rem;"></i> {{ __('Escalated') }}
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="col-id ps-4">{{ __('ID') }}</th>
                                    <th class="col-title">{{ __('Title') }}</th>
                                    <th class="col-status">{{ __('Status') }}</th>
                                    <th class="col-prio">{{ __('Priority') }}</th>
                                    <th class="col-action">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td class="ps-4"><span class="text-muted fw-bold">#{{ $ticket->id }}</span></td>
                                        <td>
                                            <div class="fw-semibold">{{ $ticket->title }}</div>
                                            <small class="text-muted">{{ $ticket->created_at->format('d M Y, H:i') }}</small>
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
                                                class="badge {{ $priorityClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $priorityClass) }} border-0">
                                                {{ __(ucfirst($ticket->priority)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('tickets.show', $ticket) }}"
                                                class="btn btn-outline-primary btn-sm rounded-pill px-3">{{ __('View') }}</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                                                    stroke-linecap="round" stroke-linejoin="round" class="mb-3">
                                                    <path
                                                        d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z">
                                                    </path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                                    <polyline points="10 9 9 9 8 9"></polyline>
                                                </svg>
                                                <p class="text-muted mb-1">{{ __('No tickets yet.') }}</p>
                                                <small class="text-muted">{!! __('Click the <b>"Create New Ticket"</b> button to create your first ticket.') !!}</small>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($tickets->hasPages())
                        <div class="px-4 py-3 border-top">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Sidebar: consolidated, no duplicate CTA ── --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">{{ __('Other Menu') }}</h6>

                        <a href="{{ route('forum.index') }}"
                            class="action-card d-flex align-items-center p-3 mb-3 text-decoration-none text-dark">
                            <div class="icon-box bg-info bg-opacity-10 text-info me-3">
                                <i class="bi bi-people fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold small">{{ __('Community Forum') }}</div>
                                <small class="text-muted">Diskusi dengan user lain</small>
                            </div>
                        </a>

                        <a href="{{ route('tickets.history') }}"
                            class="action-card d-flex align-items-center p-3 mb-3 text-decoration-none text-dark">
                            <div class="icon-box bg-secondary bg-opacity-10 text-secondary me-3">
                                <i class="bi bi-archive fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold small">{{ __('Full History') }}</div>
                                <small class="text-muted">{{ __('See all your tickets') }}</small>
                            </div>
                        </a>

                        <hr class="my-3 opacity-10">

                        <div class="bg-light rounded-3 p-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-info-circle text-primary me-2"></i>
                                <div class="fw-bold small">{{ __('About Platform') }}</div>
                            </div>
                            <p class="text-muted mb-0" style="font-size: 0.8rem;">{{ __('MasTolongMas is a queue management system for IT support, including IT procurement and feedback. Please use tickets so we can process them officially.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Floating Action Button for Mobile --}}
    <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-create-floating d-md-none">
        <i class="bi bi-plus-lg fs-4 text-white"></i>
    </a>

@endsection