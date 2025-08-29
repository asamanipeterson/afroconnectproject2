<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'participants')
            ->withPivot('joined_at');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
