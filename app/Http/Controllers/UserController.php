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
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        /** @var \App\Models\User $user */
        $user = User::with(['stories.genres', 'stories.author'])->findOrFail($user->id);

        // Lógica Inteligente de Foto
        if ($user->photo && !str_starts_with($user->photo, 'http')) {
            if (!file_exists(storage_path('app/public/' . $user->photo))) {
                if ($user->google_photo) {
                    $user->photo = $user->google_photo;
                }
            }
        }

        return $user;
    }

    // Perfil público
    public function show(User $user)
    {
        $user->load([
            'stories' => function ($q) {
                $q->withCount('likes');
            },
            'stories.genres',
            'stories.author',
            'stories.chapters',
        ])->loadCount('followers');

        // Lógica Inteligente de Foto
        if ($user->photo && !str_starts_with($user->photo, 'http')) {
            if (!file_exists(storage_path('app/public/' . $user->photo))) {
                if ($user->google_photo) {
                    $user->photo = $user->google_photo;
                }
            }
        }

        return $user;
    }

    // Stories creadas por el usuario
    public function stories(User $user)
    {
        return $user->stories()
            ->with(['genres', 'author'])
            ->get();
    }

    // Stories seguidas por el usuario
    public function follows(User $user)
    {
        return $user->follows()
            ->with('story.genres')
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
    // Stories a las que el usuario ha dado like (Guardadas)
    public function likedStories()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        return $user->likes()
            ->with(['story.genres', 'story.author'])
            ->get()
            ->pluck('story');
    }

    // Actualizar perfil
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:2048',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profiles', 'public');
            $validated['photo'] = $path;
        }

        $user->update($validated);

        $user->refresh();

        return response()->json($user);
    }

    // Eliminar cuenta
  public function destroy()
{
    /** @var \App\Models\User $user */
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
