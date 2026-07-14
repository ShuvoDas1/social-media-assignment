<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'comment',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(PostComment::class, 'parent_id')->latest();
    }

    public function reactions()
    {
        return $this->hasMany(CommentReaction::class, 'comment_id');
    }

    public function scopeReactionsCount($query)
    {
        return $query->withCount([
            'reactions as like_count' => function ($query) {
                $query->where('react', 'like');
            },
            'reactions as unlike_count' => function ($query) {
                $query->where('react', 'unlike');
            },
            'reactions as is_liked' => function ($query) {
                $query->where('react', 'like')
                    ->where('user_id', auth()->id());
            },
        ]);
    }



    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
}
