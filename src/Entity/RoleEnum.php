<?php

namespace App\Entity;

enum RoleEnum: string
{
    case ADMIN = 'ADMIN';
    case STUDENT = 'STUDENT';
    case TEACHER = 'TEACHER';
    case ENTREPRISE = 'ENTREPRISE';
    case CLUB = 'CLUB';
}

