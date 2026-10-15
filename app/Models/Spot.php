<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Spot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'destination_id',
        'name',
        'category',
        'short_description',
        'latitude',
        'longitude',
        'points_reward',
        'photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'points_reward' => 'integer',
        ];
    }

    /**
     * Get the destination this spot belongs to.
     *
     * @return BelongsTo<Destination, $this>
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Get all check-ins for this spot.
     *
     * @return HasMany<UserCheckin, $this>
     */
    public function userCheckins(): HasMany
    {
        return $this->hasMany(UserCheckin::class);
    }

    /**
     * Check if a specific user has already checked in to this spot.
     */
    public function isCheckedInBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->userCheckins()->where('user_id', $user->id)->exists();
    }
}
