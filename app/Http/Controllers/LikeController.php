<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;


class LikeController extends Controller
{
    public function toggle(Request $request)
    {
        // Asegurarnos de que el usuario esté autenticado
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        // Validación de request
        $request->validate([
            'story_id' => 'required|exists:stories,id',
        ]);

        // Buscar si ya existe un like del usuario en esa historia
        $like = Like::where('story_id', $request->story_id)
            ->where('user_id', $user->id)
            ->first();

        // Si existe, eliminarlo (unlike)
        if ($like) {
            $like->delete();
            return response()->json(['liked' => false]);
        }

        // Si no existe, crearlo (like)
        Like::create([
            'story_id' => $request->story_id,
            'user_id' => $user->id,
        ]);

        return response()->json(['liked' => true]);
    }
}
