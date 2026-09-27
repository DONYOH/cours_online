<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Notifications\Notification;

class GradePosted extends Notification
{
    public function __construct(public Submission $submission) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $assignment = $this->submission->assignment;

        return [
            'title' => 'Nouvelle note disponible',
            'message' => "« {$assignment->title} » : {$this->submission->grade}/{$assignment->max_points}",
            'url' => route('assignments.show', [$assignment->course, $assignment]),
        ];
    }
}
