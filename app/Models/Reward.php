<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reward extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'points_required',
        'stock',
        'voucher_code',
        'image_url',
        'category',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'points_required' => 'integer',
            'stock' => 'integer',
        ];
    }

    /**
     * Get all redemptions of this reward.
     *
     * @return HasMany<UserReward, $this>
     */
    public function userRewards(): HasMany
    {
        return $this->hasMany(UserReward::class);
    }
}
