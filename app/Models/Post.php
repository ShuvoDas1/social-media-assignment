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

    public function scopePublic($query)
    {
        return $query->where('visibility', self::VISIBLE_PUBLIC);
    }

    public function scopePrivate($query)
    {
        return $query->where('visibility', self::VISIBLE_PRIVATE);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOrderByCreated($query)
    {
        return $query->orderBy('created_at', 'desc');
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

}
