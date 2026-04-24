<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    /**
     * Handle Google Login from Frontend (ID Token)
     */
    public function loginWithGoogle(Request $request)
    {
        $idToken = $request->input('token');

        if (!$idToken) {
            return response()->json(['error' => 'No token provided'], 400);
        }

        // Verify token with Google
        $response = Http::get("https://oauth2.googleapis.com/tokeninfo?id_token={$idToken}");

        if ($response->failed()) {
            return response()->json(['error' => 'Invalid Google token'], 401);
        }

        $googleUser = $response->json();

        // Check if user exists by email or google_id
        $user = User::where('email', $googleUser['email'])->first();

        if ($user) {
            // Update google_id if not present
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser['sub']]);
            }
        } else {
            // Create new user
            $user = User::create([
                'name' => $googleUser['name'],
                'email' => $googleUser['email'],
                'google_id' => $googleUser['sub'],
                'username' => strtolower($googleUser['given_name']) . rand(100, 999),
                'photo' => $googleUser['picture'] ?? null,
                'email_verified_at' => now(),
            ]);
        }

        // Generate Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Login exitoso con Google'
        ]);
    }
}
