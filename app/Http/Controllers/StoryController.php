<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    public function index()
    {
        // Historias más recientes primero
        return Story::with('author', 'genres')
            ->latest()
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required |string|max:100',
            'description' => 'nullable|string',
            // Géneros opcionales, se pueden enviar como genre_id[] en el formulario
            'genre_id' => 'nullable|array',
            'genre_id.*' => 'exists:genres,id',
            // La portada se envía como archivo (FormData), no como string
            'cover_image' => 'nullable|file|image|max:2048',
        ]);

        // Guardar imagen si viene
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        $story = Story::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'user_id' => 1, // Usuario fijo por ahora, luego se cambiará por Auth::id()
            'cover_image' => $coverPath,
        ]);

        // Relacionar géneros (si se enviaron)
        $story->genres()->sync($validated['genre_id'] ?? []);

        return $story->load('author', 'genres');
    }

    public function show(Story $story)
    {
        return $story->load('author', 'genres', 'chapters');
    }

    public function update(Request $request, Story $story)
    {
        $story->update($request->only('title', 'description', 'genre_id'));
        return $story;
    }

    public function destroy(Story $story)
    {
        $story->delete();
        return response()->json(['message' => 'Story deleted']);
    }
}
