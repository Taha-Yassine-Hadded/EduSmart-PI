<?php

namespace App\Entity;

enum EtatEnum: string
{
    case AFaire = 'à faire';
    case EnCours = 'en cours';
    case Termine = 'terminée';
    public function toString(): string
    {
        return match($this) {
            self::AFaire => 'À faire',
            self::EnCours => 'En cours',
            self::Termine => 'Terminée',
        };
    }
    public static function getValidStates(): array
    {
        return [
            self::AFaire,
            self::EnCours,
            self::Termine,
        ];
    }
}

