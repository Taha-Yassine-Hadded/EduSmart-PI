<?php

namespace App\Entity;

enum EtatEnum: string
{
    case AFaire = 'à faire';
    case EnCours = 'en cours';
    case Termine = 'terminée';
}

