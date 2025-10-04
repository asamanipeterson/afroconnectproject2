<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $stream_id
 * @property int $user_id
 * @property string $content
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Stream $stream
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reaction[] $reactions
 */
class LiveComment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stream_id',
        'user_id',
        'content',
    ];

    // --- Relationships ---

    /**
     * Get the stream that owns the comment.
     */
    public function stream(): BelongsTo
    {
        return $this->belongsTo(Stream::class);
    }

    /**
     * Get the user who posted the comment.
     */
    public function user(): BelongsTo
    {
        // Assuming your user model is named 'User'
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reactions associated with the comment.
     * This relationship assumes we will create a 'Reaction' model and a 'live_comment_reactions' table soon.
     */
    public function reactions(): HasMany
    {
        // NOTE: We will define the Reaction model next!
        return $this->hasMany(Reaction::class);
    }

    // --- Accessors/Helpers (Optional) ---

    /**
     * A helper to format the creation time for UI display.
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
