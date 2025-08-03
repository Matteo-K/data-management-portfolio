<?php

namespace App\DBAL\Types;

use App\Enum\ProjectObjective;

class ProjectObjectiveType extends EnumType
{
  /**
   * Retourner la classe d'énumération ProjectObjective
   * @return string
   */
  protected function getEnumClass(): string {
    return ProjectObjective::class;
  }
}
