<?php

namespace App\Enum;

enum DataStatut: string
{
    case ACTIF = 'actif';              // Actif
    case INACTIF = 'inactif';          // Inactif
    case DELETED = 'deleted';          // Supprimé
    case DRAFT = 'draft';              // Brouillon
}
