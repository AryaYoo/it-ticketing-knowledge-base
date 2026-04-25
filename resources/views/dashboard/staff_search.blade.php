@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Back to Dashboard</a>
                <div class="card">
                    <div class="card-header">{{ __('Search Results for: ') }} "{{ $query }}"</div>

                    <div class="card-body">
                        @if($tickets->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Problem Summary</th>
                                        <th>Resolved At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td>{{ $ticket->id }}</td>
                                            <td>{{ $ticket->title }}</td>
                                            <td>{{ Str::limit($ticket->resolution_problem_summary, 50) }}</td>
                                            <td>{{ $ticket->resolved_at->format('d M Y') }}</td>
                                            <td>
                                                <a href="{{ route('tickets.show', $ticket) }}"
                                                    class="btn btn-sm btn-primary">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $tickets->appends(['query' => $query])->links() }}
                        @else
                            <p>No resolved tickets found matching your query.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
