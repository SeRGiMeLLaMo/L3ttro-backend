<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    // Aseguramos que solo usuarios autenticados puedan usar este método
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Request $request)
    {
        // Obtener el ID del usuario autenticado
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        // Validación de request
        $request->validate([
            'story_id' => 'required|exists:stories,id',
        ]);

        // Buscar si ya existe un like del usuario en esa historia
        $like = Like::where('story_id', $request->story_id)
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            // Si existe, eliminarlo (unlike)
            $like->delete();
            return response()->json(['liked' => false]);
        }

        // Si no existe, crearlo (like)
        Like::create([
            'story_id' => $request->story_id,
            'user_id' => $userId,
        ]);

        return response()->json(['liked' => true]);
    }
}
