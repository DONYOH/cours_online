<?php

namespace App\Models;

use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'prompt', 'options', 'correct', 'explanation', 'points', 'position'];

    protected function casts(): array
    {
        return [
            'type' => QuestionType::class,
            'options' => 'array',
            'correct' => 'array',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /** Options affichées à l'apprenant (Vrai/Faux géré automatiquement). */
    public function displayOptions(): array
    {
        return match ($this->type) {
            QuestionType::TrueFalse => ['Vrai', 'Faux'],
            QuestionType::Short => [],
            default => $this->options ?? [],
        };
    }
}
