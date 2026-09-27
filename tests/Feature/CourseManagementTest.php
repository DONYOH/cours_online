<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_create_a_course_with_default_section(): void
    {
        $teacher = User::factory()->teacher()->create();

        $this->actingAs($teacher)->post(route('courses.store'), [
            'title' => 'Découvrir Laravel',
            'level' => 'beginner',
            'language' => 'fr',
            'is_published' => '1',
        ])->assertRedirect();

        $course = Course::where('slug', 'decouvrir-laravel')->firstOrFail();
        $this->assertTrue($course->isOwnedBy($teacher));
        $this->assertSame(1, $course->sections()->count());
    }

    public function test_slugs_are_unique(): void
    {
        $a = Course::factory()->create(['title' => 'Même titre']);
        $b = Course::factory()->create(['title' => 'Même titre']);
        $this->assertNotSame($a->slug, $b->slug);
    }

    public function test_student_cannot_create_courses(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('courses.store'), ['title' => 'X', 'level' => 'beginner', 'language' => 'fr'])
            ->assertForbidden();
    }

    public function test_teacher_can_author_content(): void
    {
        $section = Section::factory()->create();
        $course = $section->course;
        $this->actingAs($course->teacher);

        $this->post(route('lessons.store', [$course, $section]), [
            'title' => 'Introduction', 'type' => 'video', 'video_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'duration_minutes' => 5, 'is_published' => '1',
        ])->assertRedirect(route('courses.builder', $course));
        $lesson = Lesson::first();
        $this->assertSame('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', $lesson->embedUrl());

        $this->post(route('quizzes.store', [$course, $section]), ['title' => 'Quiz 1', 'pass_score' => 60])->assertRedirect();
        $quiz = Quiz::first();

        // Choix multiples : les options vides sont retirées et les index remappés
        $this->post(route('questions.store', [$course, $quiz]), [
            'type' => 'multiple', 'prompt' => 'Langages ?', 'points' => 2,
            'options' => ['PHP', '', 'Python', 'HTML'], 'correct_multiple' => [0, 2],
        ])->assertSessionHasNoErrors();
        $q = $quiz->questions()->first();
        $this->assertSame(['PHP', 'Python', 'HTML'], $q->options);
        $this->assertSame([0, 1], $q->correct);

        // Validation : au moins une bonne réponse
        $this->post(route('questions.store', [$course, $quiz]), [
            'type' => 'single', 'prompt' => 'Sans réponse', 'points' => 1, 'options' => ['A', 'B'],
        ])->assertSessionHasErrors('options');
    }

    public function test_builder_reorders_items_across_sections(): void
    {
        $s1 = Section::factory()->create(['position' => 1]);
        $course = $s1->course;
        $s2 = Section::factory()->create(['course_id' => $course->id, 'position' => 2]);
        $lesson = Lesson::factory()->create(['section_id' => $s1->id]);

        $this->actingAs($course->teacher)->postJson(route('courses.reorder', $course), [
            'sections' => [
                ['id' => $s2->id, 'items' => [['kind' => 'lesson', 'id' => $lesson->id]]],
                ['id' => $s1->id, 'items' => []],
            ],
        ])->assertOk();

        $this->assertSame($s2->id, $lesson->fresh()->section_id);
        $this->assertSame(1, $s2->fresh()->position);
        $this->assertSame(2, $s1->fresh()->position);
    }

    public function test_quiz_generator_local_fallback_creates_questions(): void
    {
        config(['services.anthropic.key' => null]);
        $section = Section::factory()->create();
        $course = $section->course;
        Lesson::factory()->create(['section_id' => $section->id, 'content' => 'La normalisation élimine la redondance dans un schéma relationnel. Une clé étrangère garantit l\'intégrité référentielle entre deux tables.']);
        $quiz = Quiz::factory()->create(['section_id' => $section->id]);

        $this->actingAs($course->teacher)->post(route('questions.generate', [$course, $quiz]), ['count' => 2])
            ->assertSessionHas('success');

        $this->assertSame(2, $quiz->questions()->count());
        $this->assertSame('short', $quiz->questions()->first()->type->value);
    }

    public function test_gradebook_csv_export(): void
    {
        $course = Course::factory()->create();
        $student = User::factory()->create(['name' => 'Ama Kodjo']);
        $course->enrollments()->create(['user_id' => $student->id]);

        $response = $this->actingAs($course->teacher)->get(route('gradebook.export', $course));
        $response->assertOk();
        $this->assertStringContainsString('Ama Kodjo', $response->streamedContent());
    }

    public function test_admin_can_change_roles_but_not_demote_self(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)->patch(route('admin.users.update', $user), ['role' => 'teacher']);
        $this->assertTrue($user->fresh()->isTeacher());

        $this->patch(route('admin.users.update', $admin), ['role' => 'student'])->assertSessionHas('error');
        $this->assertTrue($admin->fresh()->isAdmin());
    }
}
