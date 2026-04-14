<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');

        $announcements = \App\Models\Announcement::when($search, function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.announcements.index', compact('announcements', 'search'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('announcements', 'public');
        }

        \App\Models\Announcement::create([
            'user_id' => auth()->id(),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'image_path' => $path,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('announcements.index')->with('status', 'Announcement created successfully!');
    }

    public function edit(\App\Models\Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, \App\Models\Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($announcement->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($announcement->image_path);
            }
            $announcement->image_path = $request->file('image')->store('announcements', 'public');
        }

        $announcement->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('announcements.index')->with('status', 'Announcement updated successfully!');
    }

    public function destroy(\App\Models\Announcement $announcement)
    {
        if ($announcement->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($announcement->image_path);
        }
        $announcement->delete();
        return redirect()->route('announcements.index')->with('status', 'Announcement deleted successfully!');
    }
}
