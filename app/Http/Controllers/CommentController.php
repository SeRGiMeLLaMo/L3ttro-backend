<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'story_id' => 'nullable|exists:stories,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'content' => 'required',
        ]);

        return Comment::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);
    }
}
