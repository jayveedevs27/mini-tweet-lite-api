<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTweetRequest;
use App\Http\Requests\UpdateTweetRequest;
use App\Http\Resources\TweetResource;

class TweetController extends Controller
{
    public function index()
    {
        $tweets = Tweet::with('user')
            ->withCount('likes')
            ->latest()
            ->paginate(20);

        return TweetResource::collection($tweets);
    }

    public function store(StoreTweetRequest $request)
    {
        $tweet = Tweet::create([
            'user_id' => $request->user()->id,
            'body' => $request->body,
        ]);

        return new TweetResource($tweet->load('user')->loadCount('likes'));
    }

    public function show(Tweet $tweet)
    {
        $tweet->load('user')->loadCount('likes');
        return new TweetResource($tweet);
    }

    public function update(UpdateTweetRequest $request, Tweet $tweet)
    {
        $this->authorize('update', $tweet);

        $tweet->update($request->validated());

        $tweet->load('user')->loadCount('likes');

        return new TweetResource($tweet);
    }

    public function destroy(Tweet $tweet)
    {
        $this->authorize('delete', $tweet);
        $tweet->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }
}
