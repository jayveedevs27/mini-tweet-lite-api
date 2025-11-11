<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, Tweet $tweet)
    {
        $user = $request->user();

        $existing = $tweet->likes()->where('user_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            $tweet->likes()->create([
                'user_id' => $user->id,
            ]);
            $liked = true;
        }

        $likesCount = $tweet->likes()->count();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
        ], 200);
    }
}
