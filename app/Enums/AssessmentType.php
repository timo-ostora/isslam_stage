<?php

namespace App\Enums;

enum AssessmentType: string
{
    case Quiz       = 'quiz';
    case Exam       = 'exam';
    case Assignment = 'assignment';

    public function label(): string
    {
        return match($this) {
            self::Quiz       => 'Quiz',
            self::Exam       => 'Examen',
            self::Assignment => 'Devoir',
        };
    }
}