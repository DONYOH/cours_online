<?php

namespace App\Enums;

enum QuestionType: string
{
    case Single = 'single';
    case Multiple = 'multiple';
    case TrueFalse = 'true_false';
    case Short = 'short';

    public function label(): string
    {
        return match ($this) {
            self::Single => 'Choix unique',
            self::Multiple => 'Choix multiples',
            self::TrueFalse => 'Vrai / Faux',
            self::Short => 'Réponse courte',
        };
    }

    public function hasOptions(): bool
    {
        return in_array($this, [self::Single, self::Multiple], true);
    }
}
