<?php

namespace App\Models;

use App\Enums\CourseLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'title', 'slug', 'summary', 'description', 'level', 'language',
        'enrollment_key', 'is_published', 'starts_at', 'ends_at',
    ];

    protected $hidden = ['enrollment_key'];

    protected function casts(): array
    {
        return [
            'level' => CourseLevel::class,
            'is_published' => 'boolean',
            'starts_at' => 'date',
            'ends_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            if (blank($course->slug)) {
                $course->slug = static::uniqueSlug($course->title);
            }
        });
    }

    public static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'cours';
        $slug = $base;
        $i = 2;
        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('position');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class)->latest();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->using(Enrollment::class)
            ->withPivot(['id', 'progress', 'last_activity_at', 'completed_at'])
            ->withTimestamps();
    }

    public function isOwnedBy(?User $user): bool
    {
        return $user !== null && $this->teacher_id === $user->id;
    }

    public function enrollmentFor(?User $user): ?Enrollment
    {
        if (! $user) {
            return null;
        }

        return $this->enrollments()->where('user_id', $user->id)->first();
    }

    public function durationMinutes(): int
    {
        return (int) $this->lessons()->where('is_published', true)->sum('duration_minutes');
    }

    /** Dégradé déterministe pour la vignette du cours (pas besoin d'image). */
    public function gradient(): string
    {
        $palettes = [
            'from-indigo-500 to-violet-600',
            'from-sky-500 to-cyan-500',
            'from-emerald-500 to-teal-600',
            'from-amber-500 to-orange-600',
            'from-rose-500 to-pink-600',
            'from-fuchsia-500 to-purple-600',
        ];

        return $palettes[crc32($this->slug ?? $this->title) % count($palettes)];
    }
}
