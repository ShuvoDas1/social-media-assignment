<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentResource extends JsonResource
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
            'post_id' => $this->post_id,
            'user_id' => $this->user_id,
            'parent_id' => $this->parent_id,
            'comment' => $this->comment,
            'like_count' => $this->like_count,
            'unlike_count' => $this->unlike_count,
            'replies' => $this->replies,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => [
                'id' => $this->user->id,
                'full_name' => $this->user->fname . " " . $this->user->lname,
                'email' => $this->user->email,
            ],
        ];
    }
}
