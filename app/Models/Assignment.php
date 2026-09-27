<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id', 'title', 'instructions', 'due_at', 'max_points', 'allow_late', 'position', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'allow_late' => 'boolean',
            'is_published' => 'boolean',
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

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function kind(): string
    {
        return 'assignment';
    }

    public function isOverdue(): bool
    {
        return $this->due_at !== null && now()->greaterThan($this->due_at);
    }

    public function acceptsSubmissions(): bool
    {
        return ! $this->isOverdue() || $this->allow_late;
    }
}
