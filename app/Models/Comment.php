<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'content',
        'parent_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A comment belongs to a post.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * A comment can have many replies (nested comments).
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest(); // Order replies by latest
    }

    /**
     * A comment can have one parent (if it's a reply).
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
