<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case Active    = 'active';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Active    => 'Actif',
            self::Completed => 'Terminé',
            self::Cancelled => 'Annulé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active    => 'info',
            self::Completed => 'success',
            self::Cancelled => 'danger',
        };
    }
}