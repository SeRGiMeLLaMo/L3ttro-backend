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
        ]);

        return Story::create([
            ...$validated,
            'user_id'=> 1,
        ]);
        $story->genres()->sync($request->genre_id);
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
