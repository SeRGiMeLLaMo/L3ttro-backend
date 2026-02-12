<?php
namespace App\Http\Controllers;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FollowController extends Controller
{
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $request->validate([
            'story_id' => 'required|exists:stories,id',
        ]);

        $follow = Follow::where('story_id', $request->story_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($follow) {
            $follow->delete();
            return response()->json(['following' => false]);
        }

        Follow::create([
            'story_id' => $request->story_id,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['following' => true]);
    }
}