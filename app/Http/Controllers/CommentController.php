<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, \App\Models\Ticket $ticket)
    {
        $request->validate(['content' => 'required|string']);

        $ticket->comments()->create([
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        return back()->with('status', 'Comment added!');
    }
}
