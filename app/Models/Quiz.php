<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'difficulty',
        'points_per_question',
        'time_limit_minutes',
        'total_questions',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'points_per_question' => 'integer',
            'time_limit_minutes' => 'integer',
            'total_questions' => 'integer',
        ];
    }

    /**
     * Get the category that owns this quiz.
     *
     * @return BelongsTo<QuizCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(QuizCategory::class, 'category_id');
    }

    /**
     * Get all questions for this quiz.
     *
     * @return HasMany<Question, $this>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get all history records for this quiz.
     *
     * @return HasMany<QuizHistory, $this>
     */
    public function histories(): HasMany
    {
        return $this->hasMany(QuizHistory::class);
    }

    /**
     * Get maximum potential points that can be earned from this quiz.
     */
    public function getMaxPointsAttribute(): int
    {
        $questionCount = $this->total_questions > 0 ? $this->total_questions : $this->questions()->count();

        return $questionCount * $this->points_per_question;
    }

    /**
     * Get Indonesian label for difficulty.
     */
    public function getDifficultyLabelAttribute(): string
    {
        return match ($this->difficulty) {
            'easy' => 'Mudah',
            'medium' => 'Sedang',
            'hard' => 'Sulit',
            default => ucfirst($this->difficulty),
        };
    }
}
