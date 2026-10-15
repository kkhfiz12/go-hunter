<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'city_id',
        'destination_id',
        'title',
        'slug',
        'transport_modes',
        'is_recommended',
        'recommendation_reason',
        'estimated_time',
        'total_distance',
        'estimated_cost',
        'description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'transport_modes' => 'array',
            'is_recommended' => 'boolean',
        ];
    }

    /**
     * Get the city where this trail belongs.
     *
     * @return BelongsTo<City, $this>
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the destination targeted by this trail.
     *
     * @return BelongsTo<Destination, $this>
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Get the step-by-step transit checkpoints for this trail.
     *
     * @return HasMany<TrailCheckpoint, $this>
     */
    public function checkpoints(): HasMany
    {
        return $this->hasMany(TrailCheckpoint::class)->orderBy('step_order');
    }

    /**
     * Get a human-readable list of modes.
     *
     * @return array<int, array{mode: string, label: string, color: string}>
     */
    public function getModesMetadataAttribute(): array
    {
        $modes = $this->transport_modes ?? [];
        $meta = [];

        foreach ($modes as $mode) {
            $meta[] = match (strtolower($mode)) {
                'mrt' => [
                    'mode' => 'mrt',
                    'label' => 'MRT',
                    'bg_class' => 'bg-blue-50 text-blue-700 border-blue-200',
                ],
                'transjakarta' => [
                    'mode' => 'transjakarta',
                    'label' => 'TransJakarta',
                    'bg_class' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                ],
                'krl' => [
                    'mode' => 'krl',
                    'label' => 'KRL',
                    'bg_class' => 'bg-red-50 text-red-700 border-red-200',
                ],
                'lrt' => [
                    'mode' => 'lrt',
                    'label' => 'LRT',
                    'bg_class' => 'bg-amber-50 text-amber-700 border-amber-200',
                ],
                'bus' => [
                    'mode' => 'bus',
                    'label' => 'Bus Kota',
                    'bg_class' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                ],
                'walking' => [
                    'mode' => 'walking',
                    'label' => 'Jalan Kaki',
                    'bg_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                ],
                default => [
                    'mode' => $mode,
                    'label' => ucfirst($mode),
                    'bg_class' => 'bg-slate-100 text-slate-700 border-slate-200',
                ],
            };
        }

        return $meta;
    }
}
