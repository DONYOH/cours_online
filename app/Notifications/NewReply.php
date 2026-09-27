<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Notifications\Notification;

class NewReply extends Notification
{
    public function __construct(public Reply $reply) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $discussion = $this->reply->discussion;

        return [
            'title' => 'Nouvelle réponse',
            'message' => $this->reply->author->name.' a répondu à « '.$discussion->title.' »',
            'url' => route('discussions.show', [$discussion->course, $discussion]),
        ];
    }
}
