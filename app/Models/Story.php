<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'story_type',
        'media_path',
        'text_content',
        'background',
        'caption',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // This array tells Laravel to always append these attributes when converting to JSON
    protected $appends = ['media_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    // Get full media URL (this accessor is good)
    public function getMediaUrlAttribute()
    {
        return $this->media_path
            ? Storage::disk('public')->url($this->media_path)
            : null;
    }
}
