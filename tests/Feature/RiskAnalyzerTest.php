<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Section;
use App\Models\User;
use App\Services\RiskAnalyzer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiskAnalyzerTest extends TestCase
{
    use RefreshDatabase;

    public function test_inactive_late_student_is_flagged_high_risk(): void
    {
        $section = Section::factory()->create();
        $course = $section->course;
        $course->update(['starts_at' => now()->subWeeks(6), 'ends_at' => now()->addWeeks(2)]);
        Assignment::factory()->create(['section_id' => $section->id, 'due_at' => now()->subDays(3)]);

        $active = User::factory()->create();
        $dropout = User::factory()->create();
        $course->enrollments()->create(['user_id' => $active->id, 'progress' => 80, 'last_activity_at' => now()]);
        $course->enrollments()->create(['user_id' => $dropout->id, 'progress' => 5, 'last_activity_at' => now()->subDays(20)]);

        $results = app(RiskAnalyzer::class)->analyze($course)->keyBy(fn ($r) => $r['enrollment']->user_id);

        $this->assertSame('high', $results[$dropout->id]['level']);
        $this->assertGreaterThanOrEqual(60, $results[$dropout->id]['score']);
        $this->assertNotSame('high', $results[$active->id]['level']);
        $this->assertSame($dropout->id, app(RiskAnalyzer::class)->analyze($course)->first()['enrollment']->user_id);
    }
}
