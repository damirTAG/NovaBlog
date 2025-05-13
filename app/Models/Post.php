<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Post extends Model
{
    /**
     * @OA\Schema(
     *     schema="Post",
     *     type="object",
     *     title="Post",
     *     required={"uuid", "title", "content"},
     *     @OA\Property(property="uuid", type="string", format="uuid", example="abc123ef"),
     *     @OA\Property(property="title", type="string", example="Catchy title"),
     *     @OA\Property(property="content", type="string", example="This is the full content of the post with Markdown."),
     *     @OA\Property(property="featured_image_url", type="string", format="url", nullable=true, example="https://example.com/image.jpg"),
     *     @OA\Property(property="is_published", type="boolean", example=true),
     *     @OA\Property(property="user_id", type="integer", example=5),
     *     @OA\Property(property="likes_count", type="integer", example=12),
     *     @OA\Property(property="dislikes_count", type="integer", example=3),
     *     @OA\Property(property="comments_count", type="integer", example=4),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-02T15:30:00Z")
     * )
    */
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'featured_image_url', 'is_published', 'user_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->uuid = substr(Str::uuid()->toString(), 0, 8);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    // Get like count
    public function getLikesCountAttribute()
    {
        return $this->likes()->where('is_like', true)->count();
    }
    
    // Get dislike count
    public function getDislikesCountAttribute()
    {
        return $this->likes()->where('is_like', false)->count();
    }
    
    // Get comments count
    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }
    
    // Get route key for model binding
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}