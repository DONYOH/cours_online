<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'summary', 'position'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('position');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class)->orderBy('position');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class)->orderBy('position');
    }

    /**
     * Tous les éléments (leçons, quiz, devoirs) triés par position.
     *
     * @return Collection<int, Lesson|Quiz|Assignment>
     */
    public function items(bool $publishedOnly = false): Collection
    {
        return collect()
            ->concat($this->lessons)
            ->concat($this->quizzes)
            ->concat($this->assignments)
            ->when($publishedOnly, fn ($c) => $c->where('is_published', true))
            ->sortBy('position')
            ->values();
    }
}
