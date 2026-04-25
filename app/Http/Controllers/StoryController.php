<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }
    public function index(Request $request)
    {
        $query = Story::query()
            ->with(['author', 'genres'])
            ->withCount('likes');

        $search = $request->query('q');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('author', function ($qa) use ($search) {
                      $qa->where('username', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        $genreIds = $request->query('genres', []);
        if (is_array($genreIds) && count($genreIds) > 0) {
            $query->whereHas('genres', function ($q) use ($genreIds) {
                $q->whereIn('genres.id', $genreIds);
            });
        }

        $sort = $request->query('sort', 'newest'); // newest|oldest|alpha_asc|alpha_desc
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'alpha_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'alpha_desc':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            // Géneros opcionales, se pueden enviar como genre_id[] en el formulario
            'genre_id' => 'nullable|array',
            'genre_id.*' => 'exists:genres,id',
            // La portada se envía como archivo (FormData), no como string
            'cover_image' => 'nullable|file|image|max:10240',
        ]);

        // Guardar imagen si viene
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        $story = Story::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'user_id' => Auth::id(),
            'cover_image' => $coverPath,
        ]);

        // Relacionar géneros (si se enviaron)
        $story->genres()->sync($validated['genre_id'] ?? []);

        return $story->load('author', 'genres');
    }

    public function show(Story $story)
    {
        $story->load(['author', 'genres', 'chapters'])->loadCount('likes');
        
        // Añadir si el usuario autenticado le ha dado like
        $story->liked = false;
        if (Auth::guard('sanctum')->check()) {
            $story->liked = $story->likes()->where('user_id', Auth::guard('sanctum')->id())->exists();
        }

        return $story;
    }

    public function update(Request $request, Story $story)
    {
        if (Auth::id() !== $story->user_id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        $validated = $request->validate([
            'title' => 'sometimes|string|max:100',
            'description' => 'nullable|string',
            'genre_id' => 'nullable|array',
            'genre_id.*' => 'exists:genres,id',
            'cover_image' => 'nullable|file|image|max:2048',
        ]);

        $updateData = [
            'title' => $validated['title'] ?? $story->title,
            'description' => $validated['description'] ?? $story->description,
        ];

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $updateData['cover_image'] = $coverPath;
        }

        $story->update($updateData);

        if (array_key_exists('genre_id', $validated)) {
            $story->genres()->sync($validated['genre_id'] ?? []);
        }
        return $story->load('author', 'genres')->loadCount('likes');
    }

    public function destroy(Story $story)
    {
        if (Auth::id() !== $story->user_id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        $story->delete();
        return response()->json(['message' => 'Story deleted']);
    }
}
