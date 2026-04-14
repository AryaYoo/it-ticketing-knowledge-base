<!DOCTYPE html>
<html>

<head>
    <title>{{ __('Ticket History') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin: 0;
        }

        .meta {
            margin-bottom: 20px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        td {
            border: 1px solid #dee2e6;
            padding: 10px;
            vertical-align: top;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }

        .bg-open {
            background-color: #ffc107;
            color: #000;
        }

        .bg-in_progress {
            background-color: #17a2b8;
            color: #fff;
        }

        .bg-resolved {
            background-color: #28a745;
            color: #fff;
        }

        .bg-closed {
            background-color: #6c757d;
            color: #fff;
        }

        .bg-escalated {
            background-color: #dc3545;
            color: #fff;
        }

        .bg-low {
            background-color: #17a2b8;
            color: #fff;
        }

        .bg-medium {
            background-color: #ffc107;
            color: #000;
        }

        .bg-high {
            background-color: #dc3545;
            color: #fff;
        }

        .bg-critical {
            background-color: #343a40;
            color: #fff;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="title">{{ __('IT HELPDESK - TICKET HISTORY') }}</h1>
        <p>{{ __('Report Generated on:') }} {{ now()->format('d M Y H:i') }}</p>
    </div>

    <div class="meta">
        @if($query)
            <strong>{{ __('Search Filter:') }}</strong> "{{ $query }}"
        @endif
        <div style="float: right;">
            <strong>{{ __('Total Tickets:') }}</strong> {{ $tickets->count() }}
        </div>
        <div style="clear: both;"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">{{ __('ID') }}</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('User') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Status') }}</th>
                <th style="width: 70px;">{{ __('Priority') }}</th>
                <th>{{ __('Created At') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>#{{ $ticket->id }}</td>
                    <td><strong>{{ $ticket->title }}</strong></td>
                    <td>{{ $ticket->user->name }}</td>
                    <td>{{ $ticket->category->name }}</td>
                    <td>
                        <span class="badge bg-{{ $ticket->status }}">
                            {{ strtoupper($ticket->status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $ticket->priority }}">
                            {{ strtoupper($ticket->priority) }}
                        </span>
                    </td>
                    <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} IT Helpdesk System. {{ __('Page') }} {PAGE_NUM}
    </div>
</body>

</html>