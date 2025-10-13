<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'caption',
        'post_group_id',
        'reports_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function media()
    {
        return $this->hasMany(PostMedia::class)->orderBy('order');
    }

    public function reports()
    {
        return $this->hasMany(PostReport::class);
    }
// Posts bookmarked by many users
public function bookmarkedBy()
{
    return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
}

// Check if a post is bookmarked by a specific user
public function isBookmarkedBy(User $user = null)
{
    if (!$user) return false;

    return $this->bookmarkedBy()->where('user_id', $user->id)->exists();
}

// Get number of users who bookmarked this post
public function bookmarksCount()
{
    return $this->bookmarkedBy()->count();
}

}
