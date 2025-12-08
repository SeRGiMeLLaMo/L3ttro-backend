<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'story_id' => 'required|exists:stories,id',
        ]);

        $follow = Follow::where('story_id', $request->story_id)
            ->where('user_id', auth()->id())
            ->first();

        if ($follow) {
            $follow->delete();
            return ['following' => false];
        }

        Follow::create([
            'story_id' => $request->story_id,
            'user_id' => auth()->id(),
        ]);

        return ['following' => true];
    }
}
