@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Admin Dashboard') }}</div>

                    <div class="card-body p-4 p-lg-5">
                        <!-- Stats Cards -->
                        <div class="row g-4 section-spacing">
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="card bg-primary text-white h-100 shadow-sm border-0 transition-hover">
                                    <div class="card-body d-flex flex-column justify-content-between p-4">
                                        <div>
                                            <h6 class="card-subtitle mb-2 text-white-50 text-uppercase small"
                                                style="color: rgba(255,255,255,0.8) !important; letter-spacing: 0.05em;">
                                                {{ __('Total Tickets') }}
                                            </h6>
                                            <h2 class="card-title fw-bold mb-0 text-white" style="color: white !important;">
                                                {{ $totalTickets }}
                                            </h2>
                                        </div>
                                        <div class="mt-3 opacity-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                                <line x1="12" y1="17" x2="12" y2="21"></line>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="card bg-danger text-white h-100 shadow-sm border-0">
                                    <div class="card-body d-flex flex-column justify-content-between p-4">
                                        <div>
                                            <h6 class="card-subtitle mb-2 text-white-50 text-uppercase small"
                                                style="color: rgba(255,255,255,0.8) !important; letter-spacing: 0.05em;">
                                                {{ __('Open Tickets') }}
                                            </h6>
                                            <h2 class="card-title fw-bold mb-0 text-white" style="color: white !important;">
                                                {{ $openTickets }}
                                            </h2>
                                        </div>
                                        <div class="mt-3 opacity-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="card bg-success text-white h-100 shadow-sm border-0">
                                    <div class="card-body d-flex flex-column justify-content-between p-4">
                                        <div>
                                            <h6 class="card-subtitle mb-2 text-white-50 text-uppercase small"
                                                style="color: rgba(255,255,255,0.8) !important; letter-spacing: 0.05em;">
                                                {{ __('Avg Resolution Time') }}
                                            </h6>
                                            <h2 class="card-title fw-bold mb-0 text-white" style="color: white !important;">
                                                {{ $avgResolutionHours }} <span class="fs-6 fw-normal">hrs</span>
                                            </h2>
                                        </div>
                                        <div class="mt-3 opacity-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mb-5">
                            <h5 class="mb-3 fw-bold text-muted">{{ __('Quick Actions') }}</h5>
                            <div class="row g-3">
                                <div class="col-6 col-md">
                                    <a href="{{ route('users.index') }}"
                                        class="btn btn-outline-primary w-100 p-3 d-flex flex-column align-items-center gap-2 h-100 border-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                        <span class="fw-bold text-uppercase"
                                            style="font-size: 0.65rem; letter-spacing: 0.05em;">{{ __('Users') }}</span>
                                    </a>
                                </div>
                                <div class="col-6 col-md">
                                    <a href="{{ route('announcements.index') }}"
                                        class="btn btn-outline-primary w-100 p-3 d-flex flex-column align-items-center gap-2 h-100 border-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0L22 8"></path>
                                            <path d="M2 8l8.3 13"></path>
                                            <path d="M22 8H2"></path>
                                        </svg>
                                        <span class="fw-bold text-uppercase"
                                            style="font-size: 0.65rem; letter-spacing: 0.05em;">{{ __('Announcements') }}</span>
                                    </a>
                                </div>
                                <div class="col-6 col-md">
                                    <a href="{{ route('categories.index') }}"
                                        class="btn btn-outline-primary w-100 p-3 d-flex flex-column align-items-center gap-2 h-100 border-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <line x1="8" y1="6" x2="21" y2="6"></line>
                                            <line x1="8" y1="12" x2="21" y2="12"></line>
                                            <line x1="8" y1="18" x2="21" y2="18"></line>
                                        </svg>
                                        <span class="fw-bold text-uppercase"
                                            style="font-size: 0.65rem; letter-spacing: 0.05em;">{{ __('Categories') }}</span>
                                    </a>
                                </div>
                                <div class="col-6 col-md">
                                    <a href="{{ route('activity_logs.index') }}"
                                        class="btn btn-outline-primary w-100 p-3 d-flex flex-column align-items-center gap-2 h-100 border-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                        </svg>
                                        <span class="fw-bold text-uppercase"
                                            style="font-size: 0.65rem; letter-spacing: 0.05em;">{{ __('Logs') }}</span>
                                    </a>
                                </div>
                                <div class="col-12 col-md">
                                    <a href="{{ route('tickets.history') }}"
                                        class="btn btn-outline-primary w-100 p-3 d-flex flex-column align-items-center gap-2 h-100 border-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                        </svg>
                                        <span class="fw-bold text-uppercase"
                                            style="font-size: 0.65rem; letter-spacing: 0.05em;">{{ __('History') }}</span>
                                    </a>
                                </div>
                                <div class="col-6 col-md">
                                    <a href="{{ route('ip-mappings.index') }}"
                                        class="btn btn-outline-primary w-100 p-3 d-flex flex-column align-items-center gap-2 h-100 border-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                                            <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                                            <line x1="6" y1="6" x2="6.01" y2="6"></line>
                                            <line x1="6" y1="18" x2="6.01" y2="18"></line>
                                        </svg>
                                        <span class="fw-bold text-uppercase"
                                            style="font-size: 0.65rem; letter-spacing: 0.05em;">{{ __('IP Mappings') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <h3>{{ __('Recent Tickets') }}</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Priority') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td>{{ $ticket->id }}</td>
                                            <td>{{ $ticket->title }}</td>
                                            <td>{{ $ticket->user->name }}</td>
                                            <td>
                                                <span class="badge {{ $ticket->status_badge_class }}">
                                                    {{ __(ucfirst($ticket->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ __(ucfirst($ticket->priority)) }}</td>
                                            <td>
                                                <a href="{{ route('tickets.show', $ticket) }}"
                                                    class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection