<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewAnnouncement extends Notification
{
    public function __construct(public Announcement $announcement) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Annonce · '.$this->announcement->course->title,
            'message' => $this->announcement->title.' — '.Str::limit(strip_tags($this->announcement->body), 80),
            'url' => route('courses.show', $this->announcement->course),
        ];
    }
}
