<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $tickets = \App\Models\Ticket::latest()->take(10)->get();
            $totalTickets = \App\Models\Ticket::count();
            $openTickets = \App\Models\Ticket::where('status', 'open')->count();

            // Calculate Average Resolution Time (in hours)
            // TIMESTAMPDIFF(HOUR, created_at, resolved_at) is MySQL/MariaDB syntax
            $avgResolutionHours = \App\Models\Ticket::whereNotNull('resolved_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_time')
                ->value('avg_time');

            $avgResolutionHours = round($avgResolutionHours ?? 0, 1);

            return view('dashboard.admin', compact('tickets', 'totalTickets', 'openTickets', 'avgResolutionHours'));
        } elseif ($user->role === 'staff') {
            $search = request('search');

            $tickets = \App\Models\Ticket::where(function ($query) use ($user) {
                $query->where('assigned_to_user_id', $user->id)
                    ->orWhereIn('status', ['open', 'escalated', 'in_progress', 'resolved']);
            })
                ->whereNotIn('status', ['closed', 'resolved'])
                ->when($search, function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10)
                ->appends(['search' => $search]);

            return view('dashboard.staff', compact('tickets', 'search'));
        } else {
            $search = request('search');
            $statusFilter = request('status');

            $ticketsQuery = \App\Models\Ticket::where('user_id', $user->id);

            $totalTickets = (clone $ticketsQuery)->count();
            $openTickets = (clone $ticketsQuery)->whereIn('status', ['open', 'in_progress', 'escalated'])->count();
            $resolvedTickets = (clone $ticketsQuery)->where('status', 'resolved')->count();

            $tickets = $ticketsQuery
                ->when($search, function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->when($statusFilter, function ($query) use ($statusFilter) {
                    $query->where('status', $statusFilter);
                })
                ->latest()
                ->paginate(10)
                ->appends(['search' => $search, 'status' => $statusFilter]);

            return view('dashboard.client', compact('tickets', 'search', 'totalTickets', 'openTickets', 'resolvedTickets'));
        }
    }
}
