<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id', 'title', 'description', 'time_limit_minutes', 'max_attempts',
        'pass_score', 'shuffle_questions', 'show_answers', 'due_at', 'position', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'shuffle_questions' => 'boolean',
            'show_answers' => 'boolean',
            'is_published' => 'boolean',
            'due_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('position');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function kind(): string
    {
        return 'quiz';
    }

    public function totalPoints(): int
    {
        return (int) $this->questions->sum('points');
    }

    public function attemptsLeftFor(User $user): ?int
    {
        if ($this->max_attempts === null) {
            return null;
        }

        $used = $this->attempts()->where('user_id', $user->id)->whereNotNull('submitted_at')->count();

        return max(0, $this->max_attempts - $used);
    }

    public function bestAttemptFor(User $user): ?QuizAttempt
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->whereNotNull('submitted_at')
            ->orderByDesc('percent')
            ->first();
    }
}
