<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'story_id' => 'required|exists:stories,id',
            'title' => 'required',
            'content' => 'required',
            'order' => 'nullable|integer',
        ]);

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
        $chapter->update($request->only('title', 'content', 'order'));
        return $chapter;
    }

    public function destroy(Chapter $chapter)
    {
        $chapter->delete();
        return response()->json(['message' => 'Chapter deleted']);
    }
}
