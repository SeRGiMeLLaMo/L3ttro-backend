<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'story_id' => 'required|exists:stories,id',
            'title' => 'required',
            'content' => 'required',
            'order' => 'nullable|integer',
        ]);

        $story = Story::findOrFail($validated['story_id']);
        if (Auth::id() !== $story->user_id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Si no se proporciona el orden, calcularlo automáticamente
        if (!isset($validated['order'])) {
            $lastOrder = Chapter::where('story_id', $validated['story_id'])
                ->max('order');
            $validated['order'] = ($lastOrder ?? 0) + 1;
        }

        return Chapter::create($validated);
    }

    public function show(Chapter $chapter)
    {
        return $chapter->load('story');
    }

    public function update(Request $request, Chapter $chapter)
    {
        $story = $chapter->story;
        if (Auth::id() !== $story->user_id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $chapter->update($request->only('title', 'content', 'order'));
        return $chapter;
    }

    public function destroy(Chapter $chapter)
    {
        $story = $chapter->story;
        if (Auth::id() !== $story->user_id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $chapter->delete();
        return response()->json(['message' => 'Chapter deleted']);
    }
}
