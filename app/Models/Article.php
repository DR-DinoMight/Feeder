<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'feed_id',
        'title',
        'link',
        'description',
        'content',
        'category',
        'author',
        'published_at',
        'is_read',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the feed that owns the Article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class);
    }

    //get all articles for a feed for a user (for a feed, the user is the owner)
    public function scopeForUser($query, $user)
    {
        return $query->whereHas('feed', function ($query) use ($user) {
            return $query->where('user_id', $user->id);
        });
    }
}
