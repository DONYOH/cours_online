<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Discussion;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Parcourt toutes les pages principales avec le jeu de démo, pour chaque rôle. */
class PagesSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoSeeder::class);
    }

    public function test_public_pages(): void
    {
        $course = Course::published()->first();

        $this->get('/')->assertOk()->assertSee('EduSphere');
        $this->get(route('catalog'))->assertOk()->assertSee($course->title);
        $this->get(route('catalog', ['q' => 'SQL', 'niveau' => 'intermediate']))->assertOk();
        $this->get(route('courses.show', $course))->assertOk();
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
        $this->get(route('certificates.show', Certificate::first()))->assertOk()->assertSee('Certificat de réussite');
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_student_pages(): void
    {
        $student = User::where('email', 'etudiant@edusphere.test')->first();
        $course = $student->courses()->first();
        $course->load('lessons', 'quizzes', 'assignments');

        $this->actingAs($student);
        $this->get(route('dashboard'))->assertOk()->assertSee($course->title);
        $this->get(route('courses.show', $course))->assertOk()->assertSee('Votre progression');
        $this->get(route('courses.learn', $course))->assertRedirect();
        $this->get(route('lessons.show', [$course, $course->lessons->first()]))->assertOk();
        $this->get(route('quizzes.show', [$course, $course->quizzes->first()]))->assertOk();
        $this->get(route('assignments.show', [$course, $course->assignments->first()]))->assertOk();
        $this->get(route('discussions.index', $course))->assertOk();
        $this->get(route('discussions.show', [$course, Discussion::where('course_id', $course->id)->first()]))->assertOk();
        $this->get(route('gradebook.show', $course))->assertOk()->assertSee('Moyenne actuelle');
        $this->get(route('leaderboard'))->assertOk();
        $this->get(route('notifications.index'))->assertOk();
        $this->get(route('profile.edit'))->assertOk();

        $attempt = QuizAttempt::where('user_id', $student->id)->first();
        if ($attempt) {
            $this->get(route('attempts.show', [$attempt->quiz->course, $attempt->quiz, $attempt]))->assertOk();
        }

        // Accès refusés
        $this->get(route('courses.builder', $course))->assertForbidden();
        $this->get(route('courses.analytics', $course))->assertForbidden();
        $this->get(route('courses.index'))->assertForbidden();
        $this->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_teacher_pages(): void
    {
        $teacher = User::where('email', 'prof@edusphere.test')->first();
        $course = $teacher->taughtCourses()->published()->first();
        $quiz = Quiz::where('course_id', $course->id)->first();
        $assignment = Assignment::where('course_id', $course->id)->first();
        $section = $course->sections()->first();

        $this->actingAs($teacher);
        $this->get(route('dashboard'))->assertOk()->assertSee('À corriger');
        $this->get(route('courses.index'))->assertOk();
        $this->get(route('courses.create'))->assertOk();
        $this->get(route('courses.edit', $course))->assertOk();
        $this->get(route('courses.builder', $course))->assertOk();
        $this->get(route('enrollments.index', $course))->assertOk();
        $this->get(route('courses.analytics', $course))->assertOk()->assertSee('décrochage');
        $this->get(route('gradebook.show', $course))->assertOk()->assertSee('Moyenne');
        $this->get(route('gradebook.export', $course))->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->get(route('lessons.create', [$course, $section]))->assertOk();
        $this->get(route('lessons.edit', [$course, $course->lessons()->first()]))->assertOk();
        $this->get(route('quizzes.create', [$course, $section]))->assertOk();
        $this->get(route('quizzes.edit', [$course, $quiz]))->assertOk();
        $this->get(route('quizzes.results', [$course, $quiz]))->assertOk()->assertSee('Analyse des questions');
        $this->get(route('assignments.create', [$course, $section]))->assertOk();
        $this->get(route('assignments.edit', [$course, $assignment]))->assertOk();
        $this->get(route('submissions.index', [$course, $assignment]))->assertOk();

        // Un autre enseignant ne peut pas gérer ce cours
        $other = User::where('email', 'prof2@edusphere.test')->first();
        $this->actingAs($other)->get(route('courses.builder', $course))->assertForbidden();
    }

    public function test_admin_pages(): void
    {
        $admin = User::where('email', 'admin@edusphere.test')->first();
        $course = Course::first();

        $this->actingAs($admin);
        $this->get(route('dashboard'))->assertOk()->assertSee('Utilisateurs');
        $this->get(route('admin.users.index'))->assertOk();
        $this->get(route('admin.categories.index'))->assertOk();
        $this->get(route('courses.builder', $course))->assertOk();
        $this->get(route('courses.analytics', $course))->assertOk();
    }
}
