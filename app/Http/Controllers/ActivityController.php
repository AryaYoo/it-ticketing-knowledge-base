<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $search = request('search');

        $activities = \App\Models\ActivityLog::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where('description', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(20)
            ->appends(['search' => $search]);

        return view('admin.activity_logs.index', compact('activities', 'search'));
    }

    public function check(Request $request)
    {
        $lastCheck = $request->input('last_check');

        $query = \App\Models\ActivityLog::with('user');

        if ($lastCheck) {
            $query->where('created_at', '>', \Carbon\Carbon::parse($lastCheck));
        } else {
            // First load, don't show everything, just last 5 minutes maybe? 
            // Or act as if we just started tracking.
            // Let's return empty if no last_check, clients will set it.
            return response()->json([]);
        }

        $activities = $query->latest()->get();

        return response()->json($activities);
    }
}
