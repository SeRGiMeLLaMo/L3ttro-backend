<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['toggle']);
    }

    public function status(User $user)
    {
        $count = UserFollow::where('followed_id', $user->id)->count();
        $viewerId = Auth::id();
        if (!$viewerId) {
            return response()->json(['following' => false, 'followers_count' => $count]);
        }
        $exists = UserFollow::where('follower_id', $viewerId)
            ->where('followed_id', $user->id)
            ->exists();
        return response()->json(['following' => $exists, 'followers_count' => $count]);
    }

    public function toggle(User $user)
    {
        $viewerId = Auth::id();
        if (!$viewerId) {
            return response()->json(['error' => 'No autenticado'], 401);
        }
        if ($viewerId === $user->id) {
            return response()->json(['error' => 'No puedes seguirte a ti mismo'], 422);
        }

        $existing = UserFollow::where('follower_id', $viewerId)
            ->where('followed_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $count = UserFollow::where('followed_id', $user->id)->count();
            return response()->json(['following' => false, 'followers_count' => $count]);
        }

        UserFollow::create([
            'follower_id' => $viewerId,
            'followed_id' => $user->id,
        ]);

        $count = UserFollow::where('followed_id', $user->id)->count();
        return response()->json(['following' => true, 'followers_count' => $count]);
    }
}
