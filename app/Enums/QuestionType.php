<?php

namespace App\Enums;

enum QuestionType: string
{
    case SingleChoice   = 'single_choice';
    case MultipleChoice = 'multiple_choice';
    case TrueFalse      = 'true_false';

    public function label(): string
    {
        return match($this) {
            self::SingleChoice   => 'Choix unique',
            self::MultipleChoice => 'Choix multiple',
            self::TrueFalse      => 'Vrai / Faux',
        };
    }
}