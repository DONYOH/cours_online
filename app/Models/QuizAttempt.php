<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    protected $fillable = [
        'user_id', 'question_order', 'answers', 'score', 'max_score',
        'percent', 'passed', 'started_at', 'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'question_order' => 'array',
            'answers' => 'array',
            'passed' => 'boolean',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'score' => 'float',
            'max_score' => 'float',
            'percent' => 'float',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isSubmitted(): bool
    {
        return $this->submitted_at !== null;
    }

    public function deadline(): ?\Illuminate\Support\Carbon
    {
        $limit = $this->quiz->time_limit_minutes;

        return $limit ? $this->started_at->copy()->addMinutes($limit) : null;
    }

    public function isExpired(): bool
    {
        $deadline = $this->deadline();

        // 30 secondes de tolérance réseau
        return $deadline !== null && now()->greaterThan($deadline->copy()->addSeconds(30));
    }
}
