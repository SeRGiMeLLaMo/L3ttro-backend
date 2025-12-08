<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Aseguramos que solo usuarios autenticados puedan crear comentarios
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        // Validación de los datos
        $validated = $request->validate([
            'story_id' => 'nullable|exists:stories,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'content' => 'required|string',
        ]);

        // Creamos el comentario asignando el user_id automáticamente
        $comment = Comment::create([
            'user_id' => Auth::id(),
            'story_id' => $validated['story_id'] ?? null,
            'chapter_id' => $validated['chapter_id'] ?? null,
            'content' => $validated['content'],
        ]);

        return response()->json([
            'message' => 'Comentario creado exitosamente',
            'comment' => $comment
        ], 201);
    }
}
