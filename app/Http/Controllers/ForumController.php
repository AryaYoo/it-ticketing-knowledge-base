<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        $search = request('search');

        $announcements = \App\Models\Announcement::where('is_active', true)->latest()->get();
        $posts = \App\Models\ForumPost::with('user')
            ->where('created_at', '>=', now()->subMonth())
            ->when($search, function ($query) use ($search) {
                $query->where('content', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('forum.index', compact('posts', 'announcements', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        \App\Models\ForumPost::create([
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'forum_post',
            'description' => 'Posted in forum: ' . \Illuminate\Support\Str::limit($request->input('content'), 30),
        ]);

        return redirect()->route('forum.index')->with('status', 'Post created successfully!');
    }

    public function react(\App\Models\ForumPost $post)
    {
        $existing = $post->reactions()->where('user_id', auth()->id())->first();

        if ($existing) {
            $existing->delete();
            $message = 'Reaction removed.';
        } else {
            $post->reactions()->create([
                'user_id' => auth()->id(),
                'type' => 'like',
            ]);
            $message = 'Reaction added.';
        }

        return back(); // Simple redirect back
    }
}
