<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Services\GradebookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    public function token(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['required', 'string', 'max:100'],
        ]);

        $user = User::where('email', $data['email'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => 'Identifiants invalides.']);
        }

        return response()->json(['token' => $user->createToken($data['device_name'])->plainTextToken]);
    }

    public function revoke(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Jeton révoqué.']);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role->value,
            'xp' => $user->xp,
            'level' => $user->level(),
        ]);
    }

    public function courses(Request $request): JsonResponse
    {
        $courses = Course::published()
            ->with(['teacher:id,name', 'category:id,name'])
            ->withCount('students')
            ->when($request->string('q')->toString(), fn ($q, $term) => $q->where('title', 'like', "%{$term}%"))
            ->latest()
            ->paginate(20);

        return response()->json($courses->through(fn (Course $c) => $this->courseSummary($c)));
    }

    public function myCourses(Request $request): JsonResponse
    {
        $courses = $request->user()->courses()->with('teacher:id,name')->get()
            ->map(fn (Course $c) => $this->courseSummary($c) + [
                'progress' => $c->pivot->progress,
                'completed_at' => $c->pivot->completed_at,
            ]);

        return response()->json(['data' => $courses]);
    }

    public function course(Request $request, Course $course): JsonResponse
    {
        Gate::authorize('learn', $course);
        $course->load(['sections.lessons', 'sections.quizzes', 'sections.assignments']);

        return response()->json($this->courseSummary($course) + [
            'description' => $course->description,
            'sections' => $course->sections->map(fn ($s) => [
                'id' => $s->id,
                'title' => $s->title,
                'items' => $s->items(true)->map(fn ($i) => [
                    'kind' => $i->kind(),
                    'id' => $i->id,
                    'title' => $i->title,
                ]),
            ]),
        ]);
    }

    public function grades(Request $request, Course $course, GradebookService $gradebook): JsonResponse
    {
        Gate::authorize('learn', $course);
        $columns = $gradebook->columns($course);

        return response()->json([
            'columns' => $columns,
            'grades' => $gradebook->gradesFor($course, $request->user(), $columns),
            'final' => $gradebook->finalGrade($course, $request->user()),
        ]);
    }

    private function courseSummary(Course $course): array
    {
        return [
            'id' => $course->id,
            'slug' => $course->slug,
            'title' => $course->title,
            'summary' => $course->summary,
            'level' => $course->level->value,
            'teacher' => $course->teacher?->name,
            'category' => $course->category?->name,
            'students_count' => $course->students_count ?? null,
        ];
    }
}
