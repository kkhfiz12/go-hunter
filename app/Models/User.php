<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'phone_number',
        'reputation_points',
        'total_points',
        'badge_level',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'reputation_points' => 'integer',
            'total_points' => 'integer',
        ];
    }

    /**
     * Get all price reports submitted by the user.
     *
     * @return HasMany<PriceReport, $this>
     */
    public function priceReports(): HasMany
    {
        return $this->hasMany(PriceReport::class);
    }

    /**
     * Get all forum threads created by the user.
     *
     * @return HasMany<Thread, $this>
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * Get all comments posted by the user.
     *
     * @return HasMany<Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all travel buddy requests posted by the user.
     *
     * @return HasMany<BuddyRequest, $this>
     */
    public function buddyRequests(): HasMany
    {
        return $this->hasMany(BuddyRequest::class);
    }

    /**
     * Get all reviews posted by the user.
     *
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all spot check-ins for the user.
     *
     * @return HasMany<UserCheckin, $this>
     */
    public function checkins(): HasMany
    {
        return $this->hasMany(UserCheckin::class);
    }

    /**
     * Get all quiz histories for the user.
     *
     * @return HasMany<QuizHistory, $this>
     */
    public function quizHistories(): HasMany
    {
        return $this->hasMany(QuizHistory::class);
    }

    /**
     * Get all rewards redeemed by the user.
     *
     * @return HasMany<UserReward, $this>
     */
    public function userRewards(): HasMany
    {
        return $this->hasMany(UserReward::class);
    }

    /**
     * Calculate and sync the user's badge level based on points.
     */
    public function syncBadgeLevel(): string
    {
        $points = max($this->total_points, $this->reputation_points);

        $level = match (true) {
            $points >= 600 => 'Master Penjelajah Sejarah',
            $points >= 300 => 'Ahli Sejarah Nusantara',
            $points >= 100 => 'Penjelajah Aktif',
            default => 'Penjelajah Pemula',
        };

        if ($this->badge_level !== $level) {
            $this->badge_level = $level;
            $this->saveQuietly();
        }

        return $level;
    }
}
