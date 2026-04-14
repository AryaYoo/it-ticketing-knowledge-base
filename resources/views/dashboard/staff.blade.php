@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Staff Dashboard') }}</div>

                    <div class="card-body">
                        <!-- Knowledge Base & History Navigation -->
                        <div class="mb-5">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-4">
                                <div class="flex-grow-1" style="max-width: 600px;">
                                    <h4 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                        {{ __('Knowledge Base Search') }}
                                    </h4>
                                    <form action="{{ route('tickets.search') }}" method="GET" class="d-flex gap-2">
                                        <input type="text" name="query" class="form-control border-2"
                                            placeholder="{{ __('Search resolved problems...') }}" required>
                                        <button type="submit" class="btn btn-primary px-4 fw-bold">{{ __('Search') }}</button>
                                    </form>
                                </div>
                                <div class="col-12 col-md-auto">
                                    <a href="{{ route('tickets.history') }}" class="btn btn-outline-secondary py-2 px-4 fw-bold d-flex align-items-center justify-content-center gap-2 border-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                        {{ __('Ticket History') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="opacity-10 my-4">

                        <h3 class="fw-bold h4 mb-4 text-primary d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            {{ __('Assigned & Active Tickets') }}
                        </h3>

                        <div class="mb-3">
                            <form action="{{ route('dashboard') }}" method="GET" class="d-flex gap-2" style="max-width: 400px;">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-2 border-end-0 pe-0">

                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-2 border-start-0 bg-white ps-2"

                                        placeholder="{{ __('Search ticket title...') }}"
                                        value="{{ $search ?? '' }}">
                                </div>
                                <button type="submit" class="btn btn-primary px-3 fw-bold">{{ __('Search') }}</button>
                                @if($search ?? false)
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-3 fw-bold">{{ __('Clear') }}</a>
                                @endif
                            </form>
                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Action</th>
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
                                                {{ ucfirst($ticket->status) }}
                                            </span>
                                        </td>
                                        <td>{{ ucfirst($ticket->priority) }}</td>
                                        <td>
                                            <a href="{{ route('tickets.show', $ticket) }}"
                                                class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <div class="mt-3">
                            {{ $tickets->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection