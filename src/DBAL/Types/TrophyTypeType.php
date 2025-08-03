<?php

namespace App\DBAL\Types;

use App\Enum\TrophyType;

class TrophyTypeType extends EnumType
{
  /**
   * Retourner la classe d'énumération TrophyType
   * @return string
   */
  protected function getEnumClass(): string {
    return TrophyType::class;
  }
}
