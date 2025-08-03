<?php

namespace App\DBAL\Types;

use App\Enum\DataStatut;

class DataStatutType extends EnumType
{
  /**
   * Retourner la classe d'énumération DataStatut
   * @return string
   */
  protected function getEnumClass(): string {
    return DataStatut::class;
  }
}
