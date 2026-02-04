<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $validated = $request->validate([
            'story_id' => 'nullable|exists:stories,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'content' => 'required|string',
        ]);

        $comment = Comment::create([
            'content' => $validated['content'],
            'story_id' => $validated['story_id'] ?? null,
            'chapter_id' => $validated['chapter_id'] ?? null,
            'user_id' => Auth::id(), // 👈 usuario autenticado
        ]);

        return response()->json($comment, 201);
    }
}