<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Course;
use App\Notifications\NewAnnouncement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class AnnouncementController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        Gate::authorize('manage', $course);

        $announcement = $course->announcements()->create($request->validate([
            'title' => ['required', 'string', 'max:160'],
            'body' => ['required', 'string', 'max:10000'],
        ]) + ['user_id' => $request->user()->id]);

        Notification::send($course->students, new NewAnnouncement($announcement->setRelation('course', $course)));

        return back()->with('success', 'Annonce publiée et envoyée à '.$course->students()->count().' apprenant(s).');
    }

    public function destroy(Course $course, Announcement $announcement): RedirectResponse
    {
        Gate::authorize('manage', $course);
        $announcement->delete();

        return back()->with('success', 'Annonce supprimée.');
    }
}
