<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = ['course_id', 'user_id', 'code', 'final_grade', 'issued_at'];

    protected function casts(): array
    {
        return ['issued_at' => 'datetime', 'final_grade' => 'float'];
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
