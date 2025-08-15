<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'path',
    ];

    /**
     * A photo belongs to a single item.
     * This is the inverse of the 'hasMany' relationship on the Item model.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
