<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'avatar_url',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    // Stats for user profile
    public function getPostsCountAttribute()
    {
        return $this->posts()->count();
    }
    
    public function getLikesReceivedCountAttribute()
    {
        return Like::whereIn('post_id', $this->posts()->pluck('id'))
            ->where('is_like', true)
            ->count();
    }
    
    public function getCommentsReceivedCountAttribute()
    {
        return Comment::whereIn('post_id', $this->posts()->pluck('id'))->count();
    }
}
