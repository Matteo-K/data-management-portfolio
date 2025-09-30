<?php

namespace App\DBAL\Types;

use App\Enum\RoadType;

class RoadTypeType extends EnumType
{
  /**
   * Retourner la classe d'énumération RoadType
   * @return string
   */
  protected function getEnumClass(): string {
    return RoadType::class;
  }
}
