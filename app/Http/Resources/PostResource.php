<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'content' => $this->content,
            'visibility' => $this->visibility,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_liked' => $this->is_liked,
            'user' => [
                'id' => $this->user_id,
                'full_name' => $this->user->fname . " " . $this->user->lname,
                'email' => $this->user->email,
            ],
            'images' => PostImageResource::collection($this->whenLoaded('images')),
            'counts' => [
                'like' => $this->like_count,
                'unlike' => $this->unlike_count,
                'comment' => $this->comments()->count(),
            ],
        ];
    }
}
