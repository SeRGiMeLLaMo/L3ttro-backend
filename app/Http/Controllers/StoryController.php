<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    public function index()
    {
        return Story::with('author', 'genre')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required |string|max:100',
            'description' => 'nullable|string',
            'genre_id' => 'required|array',
            'genre_id.*' => 'exists:genres,id',
            'cover_image' => 'nullable|string'
        ]);

        $story = Story::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'user_id' => 1 //Usuario fijo por ahora, luego se cambiará por Auth::id()
        ]);

 // Relacionar géneros
    $story->genres()->sync($validated['genre_id']);

    return $story->load('author', 'genres');
    }

    public function show(Story $story)
    {
        return $story->load('author', 'genre', 'chapters');
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
