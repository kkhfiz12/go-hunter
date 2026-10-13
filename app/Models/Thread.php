<?php

namespace App\Models;

use Database\Factories\ThreadFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model
{
    /** @use HasFactory<ThreadFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'destination_id',
        'title',
        'slug',
        'content',
        'category',
        'views_count',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'views_count' => 'integer',
        ];
    }

    /**
     * Get the author of the thread.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the destination associated with the thread.
     *
     * @return BelongsTo<Destination, $this>
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Get all comments on the thread.
     *
     * @return HasMany<Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the human readable label for thread category.
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'traffic_transit' => 'Transportasi & Jalur',
            'weather_update' => 'Kondisi Terkini',
            'tips' => 'Tips & Trik',
            default => 'Diskusi Umum',
        };
    }

    /**
     * Get CSS badge styling based on thread category.
     */
    public function getCategoryBadgeClassAttribute(): string
    {
        return match ($this->category) {
            'traffic_transit' => 'bg-sky-50 text-sky-700 border-sky-200 ring-sky-600/20',
            'weather_update' => 'bg-amber-50 text-amber-700 border-amber-200 ring-amber-600/20',
            'tips' => 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-emerald-600/20',
            default => 'bg-slate-50 text-slate-700 border-slate-200 ring-slate-600/20',
        };
    }

    /**
     * Check if the thread has an accepted solution comment.
     */
    public function getHasSolutionAttribute(): bool
    {
        if ($this->relationLoaded('comments')) {
            return $this->comments->contains('is_solution', true);
        }

        return $this->comments()->where('is_solution', true)->exists();
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Calculate display upvote score for Reddit/StackOverflow widget.
     */
    public function getEstimatedUpvotesAttribute(): int
    {
        $commentCount = $this->comments_count ?? ($this->relationLoaded('comments') ? $this->comments->count() : 0);

        return max(1, (int) round(($this->views_count / 15) + ($commentCount * 2)));
    }
}
