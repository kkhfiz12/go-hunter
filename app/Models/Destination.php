<?php

namespace App\Models;

use Database\Factories\DestinationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    /** @use HasFactory<DestinationFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'category',
        'city',
        'province',
        'address',
        'latitude',
        'longitude',
        'description',
        'operating_hours',
        'official_ticket_price',
        'parking_fee_motor',
        'parking_fee_car',
        'nearest_transit_info',
        'cover_image',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'operating_hours' => 'array',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'official_ticket_price' => 'decimal:2',
            'parking_fee_motor' => 'decimal:2',
            'parking_fee_car' => 'decimal:2',
        ];
    }

    /**
     * Get all price reports for this destination.
     *
     * @return HasMany<PriceReport, $this>
     */
    public function priceReports(): HasMany
    {
        return $this->hasMany(PriceReport::class);
    }

    /**
     * Get all forum threads for this destination.
     *
     * @return HasMany<Thread, $this>
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * Get all buddy requests for this destination.
     *
     * @return HasMany<BuddyRequest, $this>
     */
    public function buddyRequests(): HasMany
    {
        return $this->hasMany(BuddyRequest::class);
    }

    /**
     * Get all reviews for this destination.
     *
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Calculate fairness percentage (fair + cheap reports / total reports * 100).
     */
    public function fairnessPercentage(): int
    {
        if ($this->relationLoaded('priceReports') && $this->priceReports->isNotEmpty()) {
            $total = $this->priceReports->count();
            $fairOrCheap = $this->priceReports->whereIn('fairness_level', ['cheap', 'fair'])->count();

            return (int) round(($fairOrCheap / $total) * 100);
        }

        $total = $this->priceReports()->count();

        if ($total === 0) {
            return 0;
        }

        $fairOrCheap = $this->priceReports()
            ->whereIn('fairness_level', ['cheap', 'fair'])
            ->count();

        return (int) round(($fairOrCheap / $total) * 100);
    }

    /**
     * Get fairness percentage attribute.
     */
    public function getFairnessPercentageAttribute(): int
    {
        return $this->fairnessPercentage();
    }

    /**
     * Get breakdown counts of price reports by fairness level.
     *
     * @return array{cheap: int, fair: int, overpriced: int, total: int}
     */
    public function priceReportsBreakdown(): array
    {
        $reports = $this->relationLoaded('priceReports') ? $this->priceReports : $this->priceReports()->get();

        return [
            'cheap' => $reports->where('fairness_level', 'cheap')->count(),
            'fair' => $reports->where('fairness_level', 'fair')->count(),
            'overpriced' => $reports->where('fairness_level', 'overpriced')->count(),
            'total' => $reports->count(),
        ];
    }
}
