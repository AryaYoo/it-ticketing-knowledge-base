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

        <div class="card shadow-sm border-0">
            <div class="card-header py-4 px-4 border-bottom bg-white">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                    <h4 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        {{ __('Ticket History') }}
                    </h4>
                    
                    <form action="{{ route('tickets.history') }}" method="GET" class="d-flex gap-2 flex-grow-1" style="max-width: 400px;">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-2 border-end-0 pe-0">

                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </span>
                            <input type="text" name="query" class="form-control border-2 border-start-0 bg-white ps-2" 
                                placeholder="{{ __('Search ID, title, or description...') }}" 
                                value="{{ $query ?? '' }}">
                        </div>
                        <button type="submit" class="btn btn-primary px-3 fw-bold">{{ __('Search') }}</button>
                        <a href="{{ route('tickets.history.pdf', ['query' => $query]) }}" class="btn btn-danger px-3 fw-bold d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            {{ __('Cetak PDF') }}
                        </a>
                        @if($query)
                            <a href="{{ route('tickets.history') }}" class="btn btn-outline-secondary px-3 fw-bold">{{ __('Clear') }}</a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">{{ __('ID') }}</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">{{ __('Title') }}</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">{{ __('User') }}</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">{{ __('Category') }}</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">{{ __('Status') }}</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">{{ __('Priority') }}</th>
                                <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted text-end">
                                    {{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td class="px-4 fw-bold text-muted">#{{ $ticket->id }}</td>
                                    <td class="fw-bold">{{ $ticket->title }}</td>
                                    <td>{{ $ticket->user->name }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $ticket->category->name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $ticket->status_badge_class }}">
                                            {{ ucfirst(__($ticket->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $priorityColors = [
                                                'low' => 'bg-info',
                                                'medium' => 'bg-warning',
                                                'high' => 'bg-danger',
                                                'critical' => 'bg-dark'
                                            ];
                                        @endphp
                                        <span class="badge {{ $priorityColors[$ticket->priority] ?? 'bg-secondary' }}">
                                            {{ ucfirst(__($ticket->priority)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 text-end">
                                        <a href="{{ route('tickets.show', $ticket) }}"
                                            class="btn btn-sm btn-primary px-3 rounded-pill">
                                            {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($tickets->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection