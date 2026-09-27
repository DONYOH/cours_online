<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\GradebookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GradebookController extends Controller
{
    public function show(Request $request, Course $course, GradebookService $gradebook): View
    {
        Gate::authorize('learn', $course);

        if (Gate::allows('manage', $course)) {
            return view('gradebook.teacher', ['course' => $course] + $gradebook->matrix($course));
        }

        $columns = $gradebook->columns($course);

        return view('gradebook.student', [
            'course' => $course,
            'columns' => $columns,
            'grades' => $gradebook->gradesFor($course, $request->user(), $columns),
            'final' => $gradebook->finalGrade($course, $request->user()),
        ]);
    }

    /** Export CSV (séparateur « ; », BOM UTF-8 pour Excel). */
    public function export(Course $course, GradebookService $gradebook): StreamedResponse
    {
        Gate::authorize('manage', $course);
        ['columns' => $columns, 'rows' => $rows] = $gradebook->matrix($course);

        return response()->streamDownload(function () use ($columns, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, array_merge(['Nom', 'Email', 'Progression %'], $columns->pluck('title')->all(), ['Moyenne /20']), ';');
            foreach ($rows as $row) {
                fputcsv($out, array_merge(
                    [$row['student']->name, $row['student']->email, $row['progress']],
                    array_map(fn ($g) => $g === null ? '' : str_replace('.', ',', (string) $g), array_values($row['grades'])),
                    [$row['average'] === null ? '' : str_replace('.', ',', (string) $row['average'])],
                ), ';');
            }
            fclose($out);
        }, 'notes-'.$course->slug.'-'.now()->format('Ymd').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
