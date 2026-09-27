<?php

namespace App\Models;

use App\Enums\LessonType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id', 'title', 'type', 'content', 'video_url',
        'attachment_path', 'attachment_name', 'duration_minutes', 'position', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'type' => LessonType::class,
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

    public function completions(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lesson_completions')->withTimestamps();
    }

    public function kind(): string
    {
        return 'lesson';
    }

    /** URL d'intégration pour YouTube / Vimeo, sinon l'URL brute. */
    public function embedUrl(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([\w-]{11})~', $this->video_url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/'.$m[1];
        }

        if (preg_match('~vimeo\.com/(\d+)~', $this->video_url, $m)) {
            return 'https://player.vimeo.com/video/'.$m[1];
        }

        return $this->video_url;
    }
}
