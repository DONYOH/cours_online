<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Course;
use App\Services\RiskAnalyzer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __invoke(Course $course, RiskAnalyzer $risk): View
    {
        Gate::authorize('manage', $course);

        $enrollments = $course->enrollments()->get();

        // Activité quotidienne sur 30 jours
        $from = now()->subDays(29)->startOfDay();
        $raw = Activity::where('course_id', $course->id)
            ->where('created_at', '>=', $from)
            ->get(['user_id', 'created_at'])
            ->groupBy(fn ($a) => $a->created_at->format('Y-m-d'));

        $activity = collect(range(0, 29))->map(function ($i) use ($from, $raw) {
            $day = $from->copy()->addDays($i)->format('Y-m-d');
            $events = $raw->get($day, collect());

            return [
                'label' => Carbon::parse($day)->translatedFormat('d M'),
                'events' => $events->count(),
                'learners' => $events->pluck('user_id')->unique()->count(),
            ];
        });

        // Distribution de la progression
        $buckets = ['0-24 %' => 0, '25-49 %' => 0, '50-74 %' => 0, '75-99 %' => 0, '100 %' => 0];
        foreach ($enrollments as $e) {
            $key = match (true) {
                $e->progress >= 100 => '100 %',
                $e->progress >= 75 => '75-99 %',
                $e->progress >= 50 => '50-74 %',
                $e->progress >= 25 => '25-49 %',
                default => '0-24 %',
            };
            $buckets[$key]++;
        }

        $risks = $risk->analyze($course);

        return view('courses.analytics', [
            'course' => $course,
            'activity' => $activity,
            'buckets' => $buckets,
            'risks' => $risks,
            'kpis' => [
                'learners' => $enrollments->count(),
                'avgProgress' => $enrollments->count() ? round($enrollments->avg('progress')) : 0,
                'completion' => $enrollments->count() ? round($enrollments->whereNotNull('completed_at')->count() / $enrollments->count() * 100) : 0,
                'active7' => $enrollments->filter(fn ($e) => $e->last_activity_at?->gte(now()->subDays(7)))->count(),
                'atRisk' => $risks->where('level', 'high')->count(),
            ],
        ]);
    }
}
