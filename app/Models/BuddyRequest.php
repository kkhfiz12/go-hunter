<?php

namespace App\Models;

use Database\Factories\BuddyRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuddyRequest extends Model
{
    /** @use HasFactory<BuddyRequestFactory> */
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
        'description',
        'departure_date',
        'return_date',
        'slots_needed',
        'slots_filled',
        'estimated_budget_per_person',
        'status',
        'contact_link',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'return_date' => 'date',
            'slots_needed' => 'integer',
            'slots_filled' => 'integer',
            'estimated_budget_per_person' => 'decimal:2',
        ];
    }

    /**
     * Get the user who posted the travel buddy request.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the destination for this travel buddy request.
     *
     * @return BelongsTo<Destination, $this>
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Get remaining available slots.
     */
    public function getRemainingSlotsAttribute(): int
    {
        return max(0, $this->slots_needed - $this->slots_filled);
    }

    /**
     * Get formatted WhatsApp direct message URL with pre-filled message.
     */
    public function getWhatsappUrlAttribute(): string
    {
        $link = trim($this->contact_link);
        $destName = $this->destination?->name ?? 'Destinasi';
        $hostName = $this->user?->name ?? 'Host';
        $defaultMsg = "Halo {$hostName}, saya tertarik bergabung trip ke {$destName} melalui Go Hunter. Apakah slotnya masih tersedia?";

        if (str_starts_with($link, 'http://') || str_starts_with($link, 'https://')) {
            if (str_contains($link, 'wa.me') && ! str_contains($link, 'text=')) {
                $separator = str_contains($link, '?') ? '&' : '?';

                return $link.$separator.'text='.urlencode($defaultMsg);
            }

            return $link;
        }

        $phone = preg_replace('/[^0-9]/', '', $link);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62'.$phone;
        }

        return 'https://wa.me/'.$phone.'?text='.urlencode($defaultMsg);
    }

    /**
     * Get formatted date range string.
     */
    public function getFormattedDateRangeAttribute(): string
    {
        if (! $this->departure_date) {
            return '-';
        }

        $dep = $this->departure_date->format('d M Y');
        if ($this->return_date) {
            $ret = $this->return_date->format('d M Y');

            return "{$dep} - {$ret}";
        }

        return $dep;
    }
}
