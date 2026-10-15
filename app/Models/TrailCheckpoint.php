<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrailCheckpoint extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'trail_id',
        'step_order',
        'mode',
        'stop_name',
        'instruction',
        'duration_minutes',
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
            'step_order' => 'integer',
            'duration_minutes' => 'integer',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * Get the trail that owns this checkpoint.
     *
     * @return BelongsTo<Trail, $this>
     */
    public function trail(): BelongsTo
    {
        return $this->belongsTo(Trail::class);
    }

    /**
     * Get label for mode.
     */
    public function getModeLabelAttribute(): string
    {
        return match ($this->mode) {
            'mrt' => 'MRT Jakarta',
            'transjakarta' => 'TransJakarta',
            'krl' => 'KRL Commuter Line',
            'lrt' => 'LRT Jabodebek / Jakarta',
            'bus' => 'Bus Kota / Angkot',
            'walking' => 'Jalan Kaki',
            default => ucfirst($this->mode),
        };
    }

    /**
     * Get badge color styling for mode.
     */
    public function getModeBadgeClassAttribute(): string
    {
        return match ($this->mode) {
            'mrt' => 'bg-blue-50 text-blue-700 border-blue-200',
            'transjakarta' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'krl' => 'bg-red-50 text-red-700 border-red-200',
            'lrt' => 'bg-amber-50 text-amber-700 border-amber-200',
            'bus' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
            'walking' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
