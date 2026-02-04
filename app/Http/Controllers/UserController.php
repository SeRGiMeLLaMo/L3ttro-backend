<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Perfil del usuario autenticado
    public function me()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        return $user->load([
            'stories.genre',
            'stories.author',
        ]);
    }

    // Perfil público
    public function show(User $user)
    {
        return $user->load([
            'stories.genre',
            'stories.author',
        ]);
    }

    // Stories creadas por el usuario
    public function stories(User $user)
    {
        return $user->stories()
            ->with(['genre', 'author'])
            ->get();
    }

    // Stories seguidas por el usuario
    public function follows(User $user)
    {
        return $user->follows()
            ->with('story.genre')
            ->get()
            ->pluck('story');
    }

    // Comentarios del usuario
    public function comments(User $user)
    {
        return $user->comments()
            ->with(['story', 'chapter'])
            ->get();
    }

    // Actualizar perfil
    public function update(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    // Eliminar cuenta
   public function destroy()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado',
        ]);
    }
}