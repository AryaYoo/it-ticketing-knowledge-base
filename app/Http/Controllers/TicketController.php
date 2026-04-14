<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function create()
    {
        $categories = \App\Models\Category::where('is_active', true)->get();
        return view('tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high,critical',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ticket_images', 'public');
        }

        \App\Models\Ticket::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'priority' => $request->priority,
            'description' => $request->description,
            'client_image_path' => $path,
            'user_id' => auth()->id(),
            'status' => 'open',
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'ticket_created',
            'description' => 'Created ticket: ' . $request->title,
        ]);

        return redirect()->route('dashboard')->with('status', 'Ticket created successfully!');
    }

    public function show(\App\Models\Ticket $ticket)
    {
        if (auth()->user()->role === 'client' && $ticket->user_id !== auth()->id()) {
            abort(403);
        }
        return view('tickets.show', compact('ticket'));
    }

    public function update(Request $request, \App\Models\Ticket $ticket)
    {
        // For now only status update
        $ticket->update($request->only('status'));
        return back()->with('status', 'Ticket updated!');
    }

    public function resolveForm(\App\Models\Ticket $ticket)
    {
        if (auth()->user()->role !== 'staff' && auth()->user()->role !== 'admin') {
            abort(403);
        }
        return view('tickets.resolve', compact('ticket'));
    }

    public function resolve(Request $request, \App\Models\Ticket $ticket)
    {
        if (auth()->user()->role !== 'staff' && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'resolution_problem_summary' => 'required|string',
            'resolution_steps' => 'required|string',
            'resolution_image' => 'required|image|max:2048', // Mandatory as per requirement
        ]);

        $path = $request->file('resolution_image')->store('resolution_images', 'public');

        $ticket->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolution_problem_summary' => $request->resolution_problem_summary,
            'resolution_steps' => $request->resolution_steps,
            'resolution_image_path' => $path,
            'resolved_by_user_id' => auth()->id(),
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'ticket_resolved',
            'description' => 'Resolved ticket: ' . $ticket->title,
        ]);

        return redirect()->route('tickets.show', $ticket)->with('status', 'Ticket resolved successfully!');
    }

    public function escalate(\App\Models\Ticket $ticket)
    {
        if (auth()->user()->role !== 'staff' && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $ticket->update(['status' => 'escalated']);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'ticket_escalated',
            'description' => 'Escalated ticket: ' . $ticket->title,
        ]);

        return back()->with('status', 'Ticket escalated!');
    }

    public function inProgress(\App\Models\Ticket $ticket)
    {
        if (auth()->user()->role !== 'staff' && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $ticket->update(['status' => 'in_progress']);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'ticket_status_change',
            'description' => 'Updated ticket status to In Progress: ' . $ticket->title,
        ]);

        return back()->with('status', 'Ticket status updated to In Progress!');
    }

    public function search(Request $request)
    {
        if (auth()->user()->role !== 'staff' && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $query = $request->input('query');

        $tickets = \App\Models\Ticket::where('status', 'resolved')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('resolution_problem_summary', 'like', "%{$query}%")
                    ->orWhere('resolution_steps', 'like', "%{$query}%");
            })
            ->with(['user', 'category'])
            ->paginate(10);

        return view('dashboard.staff_search', compact('tickets', 'query'));
    }

    public function history(Request $request)
    {
        if (auth()->user()->role !== 'staff' && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $query = $request->input('query');

        $tickets = \App\Models\Ticket::with(['user', 'category'])
            ->when($query, function ($q) use ($query) {
                $q->where('id', 'like', "%{$query}%")
                    ->orWhere('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(15);

        return view('tickets.history', compact('tickets', 'query'));
    }

    public function historyPdf(Request $request)
    {
        if (auth()->user()->role !== 'staff' && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $query = $request->input('query');

        $tickets = \App\Models\Ticket::with(['user', 'category'])
            ->when($query, function ($q) use ($query) {
                $q->where('id', 'like', "%{$query}%")
                    ->orWhere('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest()
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tickets.pdf_history', compact('tickets', 'query'));

        return $pdf->download('ticket-history-' . now()->format('Y-m-d') . '.pdf');
    }
}
