<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'latitude',
        'longitude',
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
        ];
    }

    /**
     * Get all destinations in this city.
     *
     * @return HasMany<Destination, $this>
     */
    public function destinations(): HasMany
    {
        return $this->hasMany(Destination::class);
    }

    /**
     * Get all quiz categories in this city.
     *
     * @return HasMany<QuizCategory, $this>
     */
    public function quizCategories(): HasMany
    {
        return $this->hasMany(QuizCategory::class);
    }

    /**
     * Get all urban rally trails in this city.
     *
     * @return HasMany<Trail, $this>
     */
    public function trails(): HasMany
    {
        return $this->hasMany(Trail::class);
    }
}
