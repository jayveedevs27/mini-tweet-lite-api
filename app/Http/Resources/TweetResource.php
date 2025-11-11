<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TweetResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'user' => [
                'id' => $this->user->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'email' => $this->user->email,
                'profile_picture_url' => $this->user->profile_picture
                ? asset('storage/' . $this->user->profile_picture)
                : asset('default-avatar.png')
            ],
            'likes_count' => $this->whenCounted('likes'),
            'liked_by_me' => $this->isLikedBy($request->user()),
            'created_at' => $this->created_at,
        ];
    }
}
