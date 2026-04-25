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

        /* ── Hero Banner ── */
        .admin-hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, #00a852 100%);
            border-radius: 20px;
            padding: 1.5rem 2.5rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .admin-hero::before {
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

        .admin-hero h1 {
            font-size: 1.5rem;
            color: #ffffff !important;
        }

        .admin-hero .hero-date {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        /* ── Stat Cards ── */
        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow: var(--premium-shadow);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon.primary { background: rgba(var(--primary-rgb), 0.1); color: var(--primary-color); }
        .stat-icon.danger { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
        .stat-icon.success { background: rgba(25, 135, 84, 0.1); color: #198754; }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
            color: var(--text-muted);
        }

        /* ── Quick Actions ── */
        .quick-action-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.25rem;
            text-align: center;
            text-decoration: none;
            color: var(--text-main);
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.6rem;
            box-shadow: var(--premium-shadow);
            height: 100%;
        }

        .quick-action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(var(--primary-rgb), 0.12);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .quick-action-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(var(--primary-rgb), 0.08);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .quick-action-card:hover .quick-action-icon {
            background: var(--primary-color);
            color: white;
        }

        .quick-action-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* ── Section Card ── */
        .section-card {
            background: var(--card-bg);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--premium-shadow);
            border: 1px solid var(--glass-border);
        }

        .section-header {
            padding: 1.25rem 1.75rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        [data-theme="dark"] .section-header {
            border-bottom-color: rgba(255, 255, 255, 0.05);
        }

        /* ── Table ── */
        .admin-table thead th {
            background: rgba(var(--primary-rgb), 0.03);
            text-transform: uppercase;
            font-size: 0.68rem;
            letter-spacing: 0.06em;
            font-weight: 700;
            color: var(--text-muted);
            border-bottom: none;
            padding: 0.85rem 1rem;
            white-space: nowrap;
        }

        .admin-table tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
            vertical-align: middle;
            font-size: 0.9rem;
        }

        [data-theme="dark"] .admin-table tbody td {
            border-bottom-color: rgba(255, 255, 255, 0.04);
        }

        .admin-table tbody tr:hover {
            background: rgba(var(--primary-rgb), 0.02);
        }

        .priority-pill {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
        }
    </style>

    <div class="container py-1">
        {{-- Hero Banner --}}
        <div class="admin-hero mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                <div>
                    <h1 class="fw-bold mb-1">{{ __('Hello') }}, {{ Auth::user()->name }}!</h1>
                    <p class="mb-0 opacity-75">{{ __('Admin Dashboard') }} — {{ __('Manage your platform from here.') }}</p>
                </div>
                <div class="hero-date text-white-50">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="stat-icon primary">
                            <i class="bi bi-ticket-perforated-fill" style="font-size: 1.3rem;"></i>
                        </div>
                        <span class="stat-label">{{ __('Total Tickets') }}</span>
                    </div>
                    <div class="stat-value">{{ $totalTickets }}</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="stat-icon danger">
                            <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.3rem;"></i>
                        </div>
                        <span class="stat-label">{{ __('Open Tickets') }}</span>
                    </div>
                    <div class="stat-value text-danger">{{ $openTickets }}</div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="stat-icon success">
                            <i class="bi bi-clock-history" style="font-size: 1.3rem;"></i>
                        </div>
                        <span class="stat-label">{{ __('Avg Resolution Time') }}</span>
                    </div>
                    <div class="stat-value text-success">{{ $avgResolutionHours }} <span class="fs-6 fw-normal text-muted">hrs</span></div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="mb-4">
            <h6 class="fw-bold text-muted text-uppercase small mb-3" style="letter-spacing: 0.08em;">{{ __('Quick Actions') }}</h6>
            <div class="row g-3">
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('users.index') }}" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="bi bi-people-fill" style="font-size: 1.1rem;"></i>
                        </div>
                        <span class="quick-action-label">{{ __('Users') }}</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('announcements.index') }}" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="bi bi-megaphone-fill" style="font-size: 1.1rem;"></i>
                        </div>
                        <span class="quick-action-label">{{ __('Announcements') }}</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('categories.index') }}" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="bi bi-tags-fill" style="font-size: 1.1rem;"></i>
                        </div>
                        <span class="quick-action-label">{{ __('Categories') }}</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('activity_logs.index') }}" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="bi bi-activity" style="font-size: 1.1rem;"></i>
                        </div>
                        <span class="quick-action-label">{{ __('Logs') }}</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('tickets.history') }}" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="bi bi-journal-text" style="font-size: 1.1rem;"></i>
                        </div>
                        <span class="quick-action-label">{{ __('History') }}</span>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('ip-mappings.index') }}" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="bi bi-hdd-network-fill" style="font-size: 1.1rem;"></i>
                        </div>
                        <span class="quick-action-label">{{ __('IP Mappings') }}</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Tickets Table --}}
        <div class="section-card">
            <div class="section-header">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-inbox-fill text-primary"></i>
                    {{ __('Recent Tickets') }}
                </h6>
                <a href="{{ route('tickets.history') }}" class="text-decoration-none small fw-semibold text-primary">
                    {{ __('View All') }} <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 60px;">{{ __('ID') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('User') }}</th>
                            <th style="width: 120px;">{{ __('Status') }}</th>
                            <th style="width: 100px;">{{ __('Priority') }}</th>
                            <th style="width: 80px;">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td class="ps-4"><span class="text-muted fw-bold">#{{ $ticket->id }}</span></td>
                                <td>
                                    <div class="fw-semibold">{{ $ticket->title }}</div>
                                    <small class="text-muted">{{ $ticket->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width: 28px; height: 28px; border-radius: 50%; background: rgba(var(--primary-rgb), 0.1); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.7rem; flex-shrink: 0;">
                                            {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                                        </div>
                                        <span class="small">{{ $ticket->user->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $ticket->status_badge_class }}">
                                        {{ __(ucfirst($ticket->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $pClass = 'bg-info';
                                        if ($ticket->priority == 'high') $pClass = 'bg-danger';
                                        if ($ticket->priority == 'medium') $pClass = 'bg-warning';
                                        if ($ticket->priority == 'critical') $pClass = 'bg-danger';
                                    @endphp
                                    <span class="priority-pill {{ $pClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $pClass) }}">
                                        {{ __(ucfirst($ticket->priority)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">{{ __('View') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center py-5">
                                        <i class="bi bi-inbox text-muted opacity-25" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 mb-0">{{ __('No tickets yet.') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
