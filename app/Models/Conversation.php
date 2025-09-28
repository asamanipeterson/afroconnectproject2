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
    public static function firstOrCreateConversation($user1Id, $user2Id)
    {
        $conversation = self::whereHas('participants', function ($query) use ($user1Id) {
            $query->where('user_id', $user1Id);
        })->whereHas('participants', function ($query) use ($user2Id) {
            $query->where('user_id', $user2Id);
        })->first();

        if (!$conversation) {
            $conversation = self::create();
            $conversation->participants()->attach([$user1Id, $user2Id]);
        }

        return $conversation;
    }
}
