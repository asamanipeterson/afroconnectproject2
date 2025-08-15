<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'category',
        'condition',
        'description',
        'location',
        'user_id',
    ];

    /**
     * An item has many photos.
     * This defines the one-to-many relationship.
     */
    public function photos()
    {
        return $this->hasMany(ItemPhoto::class);
    }

    /**
     * An item belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
