<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    public const VISIBLE_PUBLIC = 'public';
    public const VISIBLE_PRIVATE = 'private';

    protected $fillable = [
        'user_id',
        'content',
        'visibility',
    ];


    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOrderByCreated($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeVisibleTo($query)
    {
        return $query->where(function ($q) {
            $q->where('visibility', self::VISIBLE_PUBLIC)
                ->orWhere(function ($sub) {
                    $sub->where('visibility', self::VISIBLE_PRIVATE)
                        ->where('user_id', auth()->id());
                });
        });
    }

    public function isPublic()
    {
        return $this->visibility === self::VISIBLE_PUBLIC;
    }

    public function isPrivate()
    {
        return $this->visibility === self::VISIBLE_PRIVATE;
    }


    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reactions()
    {
        return $this->hasMany(PostReaction::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class)->topLevel()->reactionsCount()->latest()->with('user', 'reactions', 'replies');
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

}
