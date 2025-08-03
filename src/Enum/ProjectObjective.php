<?php

namespace App\Enum;

enum ProjectObjective: string
{
    case PERSONAL = 'personal';        // Projet personnel
    case COMPANY = 'company';          // Projet pour une entreprise
    case SCHOOL = 'school';            // Projet pour une école
}
