<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\OneTimePassCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;

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
        'is_blocked',
        'is_verified',
        'is_suspended',
        'reports_count', // Added for user reporting
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['profile_picture_url'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_blocked' => 'boolean',
            'is_verified' => 'boolean',
            'is_suspended' => 'boolean',
        ];
    }

    public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture
            ? Storage::disk('public')->url($this->profile_picture)
            : asset('default-avatar.png');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function hasLiked(Post $post)
    {
        return $this->likes()->where('post_id', $post->id)->exists();
    }

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

    public function reports()
    {
        return $this->hasMany(Report::class, 'reported_user_id');
    }
    // app/Models/User.php

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'participants')
            ->withTimestamps()
            ->latest();
    }

    public function oneTimePassword()
    {
        return $this->hasOne(OneTimePassCode::class);
    }


    public function liveComments()
    {
        return $this->hasMany(LiveComment::class);
    }

    public function generateOtp()
    {
        // Generate OTP
        $otpCode = rand(100000, 999999);

        // Delete old OTP if exists
        $this->oneTimePassword()->delete();

        // Save new OTP
        $this->oneTimePassword()->create([
            'code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        // Send OTP mail
        Mail::to($this->email)->queue(new OtpMail($otpCode));

        return $otpCode;
    }


    // Many-to-Many relationship between User and Post through 'bookmarks' pivot table


    // Check if user has bookmarked a specific post
    public function hasBookmarked(Post $post)
    {
        return $this->bookmarkedPosts()->where('post_id', $post->id)->exists();
    }

    // Posts the user has bookmarked
    public function bookmarkedPosts()
    {
        return $this->belongsToMany(Post::class, 'bookmarks', 'user_id', 'post_id')->withTimestamps();
    }

    // Posts the user is tagged in
    public function taggedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_user_tags', 'user_id', 'post_id')->withTimestamps();
    }
}
