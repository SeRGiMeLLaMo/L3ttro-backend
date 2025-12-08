<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function index()
    {
        return Story::with('author', 'genre')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'genre_id' => 'required|exists:genres,id',
        ]);

        return Story::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);
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
