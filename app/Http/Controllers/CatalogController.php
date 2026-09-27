<?php

namespace App\Http\Controllers;

use App\Enums\CourseLevel;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function home(): View
    {
        return view('home', [
            'featured' => Course::published()->with(['teacher', 'category'])->withCount('students')
                ->orderByDesc('students_count')->take(6)->get(),
            'stats' => [
                'courses' => Course::published()->count(),
                'learners' => User::where('role', 'student')->count(),
                'certificates' => Certificate::count(),
            ],
        ]);
    }

    public function index(Request $request): View
    {
        $courses = Course::published()
            ->with(['teacher', 'category'])
            ->withCount(['students', 'lessons'])
            ->when($request->string('q')->trim()->toString(), function ($query, $term) {
                $query->where(fn ($q) => $q->where('title', 'like', "%{$term}%")
                    ->orWhere('summary', 'like', "%{$term}%"));
            })
            ->when($request->string('categorie')->toString(), fn ($q, $slug) => $q->whereHas('category', fn ($c) => $c->where('slug', $slug)))
            ->when($request->string('niveau')->toString(), fn ($q, $level) => $q->where('level', $level))
            ->when($request->string('tri')->toString() === 'populaires', fn ($q) => $q->orderByDesc('students_count'), fn ($q) => $q->latest())
            ->paginate(12)
            ->withQueryString();

        return view('catalog', [
            'courses' => $courses,
            'categories' => Category::orderBy('name')->get(),
            'levels' => CourseLevel::cases(),
            'enrolledIds' => $request->user()?->enrollments()->pluck('course_id')->all() ?? [],
        ]);
    }
}
