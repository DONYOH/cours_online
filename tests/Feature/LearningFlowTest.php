<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Section;
use App\Models\User;
use App\Notifications\GradePosted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LearningFlowTest extends TestCase
{
    use RefreshDatabase;

    private Course $course;

    private Lesson $lesson;

    private Quiz $quiz;

    private Assignment $assignment;

    protected function setUp(): void
    {
        parent::setUp();

        $section = Section::factory()->create();
        $this->course = $section->course;
        $this->lesson = Lesson::factory()->create(['section_id' => $section->id, 'position' => 1]);
        $this->quiz = Quiz::factory()->create(['section_id' => $section->id, 'position' => 2, 'pass_score' => 50]);
        Question::factory()->create(['quiz_id' => $this->quiz->id, 'options' => ['Paris', 'Lomé'], 'correct' => [1], 'points' => 2]);
        Question::factory()->create(['quiz_id' => $this->quiz->id, 'type' => 'short', 'options' => [], 'correct' => ['Python'], 'points' => 1, 'position' => 2]);
        $this->assignment = Assignment::factory()->create(['section_id' => $section->id, 'position' => 3]);
    }

    public function test_enrollment_respects_the_enrollment_key(): void
    {
        $this->course->update(['enrollment_key' => 'SECRET']);
        $student = User::factory()->create();

        $this->actingAs($student)->post(route('enrollments.store', $this->course), ['enrollment_key' => 'wrong'])
            ->assertSessionHasErrors('enrollment_key');
        $this->assertFalse($student->isEnrolledIn($this->course));

        $this->post(route('enrollments.store', $this->course), ['enrollment_key' => 'SECRET'])
            ->assertRedirect(route('courses.learn', $this->course));
        $this->assertTrue($student->isEnrolledIn($this->course));
    }

    public function test_unenrolled_student_cannot_access_content(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('lessons.show', [$this->course, $this->lesson]))
            ->assertForbidden();
    }

    public function test_draft_course_is_hidden_from_students(): void
    {
        $draft = Course::factory()->draft()->create();
        $this->actingAs(User::factory()->create())->get(route('courses.show', $draft))->assertForbidden();
        $this->actingAs(User::factory()->create())->post(route('enrollments.store', $draft))->assertForbidden();
    }

    public function test_full_learning_path_updates_progress_xp_and_issues_certificate(): void
    {
        Storage::fake('local');
        Notification::fake();
        $student = User::factory()->create();
        $this->actingAs($student)->post(route('enrollments.store', $this->course));

        // 1. Leçon
        $this->post(route('lessons.complete', [$this->course, $this->lesson]))->assertRedirect();
        $this->assertSame(33, $this->course->enrollmentFor($student)->progress);
        $this->assertSame(10, $student->fresh()->xp);

        // Re-valider la même leçon ne donne pas d'XP en double
        $this->post(route('lessons.complete', [$this->course, $this->lesson]));
        $this->assertSame(10, $student->fresh()->xp);

        // 2. Quiz : démarrage puis soumission (réponse courte avec faute de casse/accent)
        $this->post(route('attempts.store', [$this->course, $this->quiz]))->assertRedirect();
        $attempt = $this->quiz->attempts()->first();
        $questions = $this->quiz->questions;
        $this->post(route('attempts.submit', [$this->course, $this->quiz, $attempt]), [
            'answers' => [$questions[0]->id => 1, $questions[1]->id => ' python '],
        ])->assertRedirect(route('attempts.show', [$this->course, $this->quiz, $attempt]));

        $attempt->refresh();
        $this->assertEquals(100.0, $attempt->percent);
        $this->assertTrue($attempt->passed);
        $this->assertSame(66, $this->course->enrollmentFor($student)->progress);
        $this->get(route('attempts.show', [$this->course, $this->quiz, $attempt]))->assertOk()->assertSee('Quiz réussi');

        // 3. Devoir avec fichier
        $this->post(route('submissions.store', [$this->course, $this->assignment]), [
            'content' => 'Mon travail',
            'file' => UploadedFile::fake()->create('rapport.pdf', 100, 'application/pdf'),
        ])->assertRedirect();

        $enrollment = $this->course->enrollmentFor($student);
        $this->assertSame(100, $enrollment->progress);
        $this->assertNotNull($enrollment->completed_at);
        $this->assertDatabaseHas('certificates', ['course_id' => $this->course->id, 'user_id' => $student->id]);
        $this->assertSame(10 + 30 + 25 + 100, $student->fresh()->xp);

        // 4. Correction par l'enseignant + notification
        $submission = $this->assignment->submissions()->first();
        $this->actingAs($this->course->teacher)
            ->put(route('submissions.grade', [$this->course, $this->assignment, $submission]), ['grade' => 15, 'feedback' => 'Bien'])
            ->assertRedirect();
        $this->assertEquals(15.0, $submission->fresh()->grade);
        Notification::assertSentTo($student, GradePosted::class);

        // 5. Un devoir noté ne peut plus être modifié
        $this->actingAs($student)->post(route('submissions.store', [$this->course, $this->assignment]), ['content' => 'v2'])
            ->assertSessionHas('error');
    }

    public function test_quiz_attempt_limit_is_enforced(): void
    {
        $this->quiz->update(['max_attempts' => 1]);
        $student = User::factory()->create();
        $this->actingAs($student)->post(route('enrollments.store', $this->course));

        $this->post(route('attempts.store', [$this->course, $this->quiz]));
        $attempt = $this->quiz->attempts()->first();
        $this->post(route('attempts.submit', [$this->course, $this->quiz, $attempt]), ['answers' => []]);

        $this->post(route('attempts.store', [$this->course, $this->quiz]))->assertSessionHas('error');
        $this->assertSame(1, $this->quiz->attempts()->count());
    }

    public function test_student_cannot_view_someone_elses_attempt(): void
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        foreach ([$a, $b] as $u) {
            $this->actingAs($u)->post(route('enrollments.store', $this->course));
        }
        $this->actingAs($a)->post(route('attempts.store', [$this->course, $this->quiz]));
        $attempt = $this->quiz->attempts()->first();

        $this->actingAs($b)->get(route('attempts.show', [$this->course, $this->quiz, $attempt]))->assertForbidden();
    }

    public function test_late_submission_is_flagged_or_refused(): void
    {
        $student = User::factory()->create();
        $this->actingAs($student)->post(route('enrollments.store', $this->course));

        $this->assignment->update(['due_at' => now()->subDay(), 'allow_late' => true]);
        $this->post(route('submissions.store', [$this->course, $this->assignment]), ['content' => 'En retard']);
        $this->assertTrue($this->assignment->submissions()->first()->is_late);

        $this->assignment->submissions()->delete();
        $this->assignment->update(['allow_late' => false]);
        $this->post(route('submissions.store', [$this->course, $this->assignment]), ['content' => 'Trop tard'])->assertSessionHas('error');
        $this->assertSame(0, $this->assignment->submissions()->count());
    }

    public function test_forum_discussion_reply_and_solution(): void
    {
        $student = User::factory()->create();
        $this->actingAs($student)->post(route('enrollments.store', $this->course));
        $this->post(route('discussions.store', $this->course), ['title' => 'Question', 'body' => 'Comment faire ?'])->assertRedirect();
        $discussion = $this->course->discussions()->first();

        $teacher = $this->course->teacher;
        $this->actingAs($teacher)->post(route('discussions.reply', [$this->course, $discussion]), ['body' => 'Comme ceci.']);
        $reply = $discussion->replies()->first();

        $this->actingAs($student)->post(route('discussions.solution', [$this->course, $discussion, $reply]))->assertRedirect();
        $this->assertTrue($reply->fresh()->is_solution);
        $this->assertSame(1, $student->unreadNotifications()->count());
    }
}
