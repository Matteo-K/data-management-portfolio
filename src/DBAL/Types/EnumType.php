<?php

namespace App\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use MyCLabs\Enum\Enum;

class EnumType extends StringType
{
    public const ENUM = 'enum';

    /**
     * Convertir la valeur de la base de données en une instance de l'énumération
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return Enum|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value !== null) {
            return $this->getEnumClass()::from($value);
        }

        return null;
    }

    /**
     * Convertir l'énumération en une chaîne de caractères à stocker dans la base de données
     *
     * @param Enum|null $value
     * @param AbstractPlatform $platform
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value !== null ? $value->value : null;
    }

    /**
     * Déclaration du type SQL
     *
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "VARCHAR(255)";
    }

    /**
     * Retourner le nom du type personnalisé
     *
     * @return string
     */
    public function getName()
    {
        return self::ENUM;
    }

    /**
     * Récupérer la classe d'énumération à utiliser pour cette colonne.
     * Cette méthode devra être surchargée dans chaque type spécifique.
     *
     * @return string
     */
    protected function getEnumClass(): string
    {
        throw new \LogicException("La méthode getEnumClass() doit être implémentée.");
    }

}
