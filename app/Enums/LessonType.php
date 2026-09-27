<?php

namespace App\Enums;

enum LessonType: string
{
    case Text = 'text';
    case Video = 'video';
    case File = 'file';

    public function label(): string
    {
        return match ($this) {
            self::Text => 'Texte',
            self::Video => 'Vidéo',
            self::File => 'Document',
        };
    }
}
