<?php

// app/Models/SharedPost.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'sharer_id',
        'recipient_id',
        'post_id',
        'message',
    ];

    public function sharer()
    {
        return $this->belongsTo(User::class, 'sharer_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
