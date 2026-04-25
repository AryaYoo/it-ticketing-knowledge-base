@extends('layouts.app')

@section('content')
<style>
    .history-container {
        background: var(--card-bg);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    [data-theme="dark"] .history-container {
        border-color: rgba(255, 255, 255, 0.05);
    }

    .history-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    [data-theme="dark"] .history-header {
        border-bottom-color: rgba(255, 255, 255, 0.05);
    }

    .filter-btn {
        border: 1px solid rgba(0, 0, 0, 0.08);
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

    .filter-btn:hover, .filter-btn.active {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }

    .search-box {
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .search-box .form-control {
        padding-left: 2.5rem;
        border-radius: 10px;
        background: var(--bg-color);
        border: 1px solid rgba(0, 0, 0, 0.08);
        font-size: 0.875rem;
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

    .empty-state {
        padding: 3rem 1rem;
    }

    .empty-state svg {
        color: var(--text-muted);
        opacity: 0.2;
    }
</style>

<div class="container py-4">
    {{-- Back link --}}
    <div class="mb-3">
        <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> {{ __('Back to Dashboard') }}
        </a>
    </div>

    <div class="history-container">
        <div class="history-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <div>
                    <h4 class="fw-bold mb-1">{{ __('Full Ticket History') }}</h4>
                    <p class="text-muted small mb-0">{{ __('All tickets you have ever created') }}</p>
                </div>
                <form action="{{ route('tickets.history') }}" method="GET" class="search-box" style="min-width: 240px;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="query" class="form-control form-control-sm" placeholder="{{ __('Search ID or title...') }}" value="{{ $query ?? '' }}">
                    @if($statusFilter ?? false)
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                    @endif
                </form>
            </div>

            {{-- Filter chips --}}
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('tickets.history', array_merge(request()->except('status', 'page'), [])) }}"
                    class="filter-btn {{ !($statusFilter ?? false) ? 'active' : '' }}">{{ __('All') }}</a>
                <a href="{{ route('tickets.history', array_merge(request()->except('page'), ['status' => 'open'])) }}"
                    class="filter-btn {{ ($statusFilter ?? '') === 'open' ? 'active' : '' }}">
                    <i class="bi bi-circle-fill text-warning me-1" style="font-size: 0.5rem;"></i> Open
                </a>
                <a href="{{ route('tickets.history', array_merge(request()->except('page'), ['status' => 'in_progress'])) }}"
                    class="filter-btn {{ ($statusFilter ?? '') === 'in_progress' ? 'active' : '' }}">
                    <i class="bi bi-circle-fill text-info me-1" style="font-size: 0.5rem;"></i> In Progress
                </a>
                <a href="{{ route('tickets.history', array_merge(request()->except('page'), ['status' => 'resolved'])) }}"
                    class="filter-btn {{ ($statusFilter ?? '') === 'resolved' ? 'active' : '' }}">
                    <i class="bi bi-circle-fill text-success me-1" style="font-size: 0.5rem;"></i> Resolved
                </a>
                <a href="{{ route('tickets.history', array_merge(request()->except('page'), ['status' => 'escalated'])) }}"
                    class="filter-btn {{ ($statusFilter ?? '') === 'escalated' ? 'active' : '' }}">
                    <i class="bi bi-circle-fill text-danger me-1" style="font-size: 0.5rem;"></i> Escalated
                </a>
                <a href="{{ route('tickets.history', array_merge(request()->except('page'), ['status' => 'closed'])) }}"
                    class="filter-btn {{ ($statusFilter ?? '') === 'closed' ? 'active' : '' }}">
                    <i class="bi bi-circle-fill text-secondary me-1" style="font-size: 0.5rem;"></i> Closed
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 60px;">{{ __('ID') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th style="width: 110px;">{{ __('Category') }}</th>
                        <th style="width: 120px;">{{ __('Status') }}</th>
                        <th style="width: 100px;">{{ __('Priority') }}</th>
                        <th style="width: 130px;">{{ __('Date') }}</th>
                        <th style="width: 80px;">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td class="ps-4"><span class="text-muted fw-bold">#{{ $ticket->id }}</span></td>
                            <td>
                                <div class="fw-semibold">{{ $ticket->title }}</div>
                                <small class="text-muted d-inline-block text-truncate" style="max-width: 300px;">{{ Str::limit($ticket->description, 60) }}</small>
                            </td>
                            <td>
                                @if($ticket->category)
                                    <span class="badge bg-light text-dark border">{{ $ticket->category->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $ticket->status_badge_class }}">
                                    {{ __(ucfirst($ticket->status)) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $priorityClass = 'bg-info';
                                    if ($ticket->priority == 'high') $priorityClass = 'bg-danger';
                                    if ($ticket->priority == 'medium') $priorityClass = 'bg-warning';
                                    if ($ticket->priority == 'critical') $priorityClass = 'bg-danger';
                                @endphp
                                <span class="badge {{ $priorityClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $priorityClass) }} border-0">
                                    {{ __(ucfirst($ticket->priority)) }}
                                </span>
                            </td>
                            <td>
                                <div class="small">{{ $ticket->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $ticket->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">{{ __('View') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mb-3">
                                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                    <p class="text-muted mb-1">{{ __('No tickets found.') }}</p>
                                    @if($query ?? false)
                                        <small class="text-muted">{{ __('Try changing your search keywords.') }}</small>
                                    @else
                                        <small class="text-muted">{{ __('You haven\'t created any tickets yet.') }}</small>
                                    @endif
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
@endsection
