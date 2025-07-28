<?php

namespace App\Models;

// Make sure to import Storage
use Illuminate\Support\Facades\Storage; // <-- ADD THIS LINE

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'gender',
        'language',
        'location',
        'phone',
        'dob',
        'profile_picture',
        'bio',
        'cover_picture',
        'website',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // ADD THIS LINE: This tells Laravel to include 'profile_picture_url' when converting the model to an array/JSON
    protected $appends = ['profile_picture_url'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ADD THIS METHOD: Accessor to get the full URL of the profile picture
    public function getProfilePictureUrlAttribute()
    {
        // If profile_picture exists, return its public URL from storage
        // Otherwise, return a path to a default avatar image
        return $this->profile_picture
            ? Storage::disk('public')->url($this->profile_picture)
            : asset('default-avatar.png'); // You might need to create this image in public/images
    }

    /**
     * Get the posts created by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Post>
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Check if the user has liked a given post.
     */
    public function hasLiked(Post $post)
    {
        return $this->likes()->where('post_id', $post->id)->exists();
    }

    // public function profile()
    // {
    //     return $this->hasOne(Profile::class);
    // }

    // Users this user is following
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id')
            ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id')
            ->withTimestamps();
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }

    public function toggleFollow(User $user)
    {
        if ($this->isFollowing($user)) {
            return $this->following()->detach($user->id);
        }

        return $this->following()->attach($user->id);
    }

    public function stories()
    {
        return $this->hasMany(Story::class);
    }
}
