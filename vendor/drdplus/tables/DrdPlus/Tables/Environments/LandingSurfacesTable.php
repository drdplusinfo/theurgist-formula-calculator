<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Environments;

use DrdPlus\Codes\Environment\LandingSurfaceCode;
use DrdPlus\BaseProperties\Agility;
use DrdPlus\Tables\Partials\AbstractFileTable;
use Granam\Integer\IntegerObject;
use Granam\Integer\PositiveInteger;

/**
 * See PPH page 119 right column, @link https://pph.drdplus.info/#tabulka_povrchu
 */
class LandingSurfacesTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/landing_surfaces.csv';
    }

    public const POWER_OF_WOUND_MODIFIER = 'power_of_wound_modifier';
    public const AGILITY_MULTIPLIER = 'agility_multiplier';
    public const ARMOR_MAX_PROTECTION = 'armor_max_protection';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::POWER_OF_WOUND_MODIFIER => self::INTEGER,
            self::AGILITY_MULTIPLIER => self::POSITIVE_INTEGER,
            self::ARMOR_MAX_PROTECTION => self::POSITIVE_INTEGER,
        ];
    }

    public const SURFACE = 'surface';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::SURFACE];
    }

    /**
     * @param LandingSurfaceCode $landingSurfaceCode
     * @param Agility $agility
     * @param PositiveInteger $armorProtection
     * @return IntegerObject
     */
    public function getBaseOfWoundsModifier(
        LandingSurfaceCode $landingSurfaceCode,
        Agility $agility,
        PositiveInteger $armorProtection
    ): IntegerObject
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $row = $this->getRow($landingSurfaceCode);
        $baseOfWoundsModifier = $this->createBaseOfWoundsModifier($row);
        $agilityMultiplierBonus = $row[self::AGILITY_MULTIPLIER];
        if ($agilityMultiplierBonus) {
            if ($agility->getValue() > 0) {
                $baseOfWoundsModifier = $this->lowerByPositiveAgility(
                    $baseOfWoundsModifier,
                    $agility,
                    $agilityMultiplierBonus
                );
            } elseif ($agility->getValue() < 0) {
                $baseOfWoundsModifier = $this->increaseByNegativeAgility($baseOfWoundsModifier, $agility);
            }
        }
        $armorMaxProtection = $row[self::ARMOR_MAX_PROTECTION];
        if ($armorMaxProtection) {
            if ($armorProtection->getValue() > $armorMaxProtection) {
                $baseOfWoundsModifier = $this->lowerByArmorMaximalProtection($armorMaxProtection, $baseOfWoundsModifier);
            } else {
                $baseOfWoundsModifier = $this->lowerByArmor($armorProtection, $baseOfWoundsModifier);
            }
        }

        return $baseOfWoundsModifier;
    }

    private function createBaseOfWoundsModifier(array $row): IntegerObject
    {
        return new IntegerObject($row[self::POWER_OF_WOUND_MODIFIER]);
    }

    private function lowerByPositiveAgility(
        IntegerObject $baseOfWoundsModifier,
        Agility $agility,
        int $agilityMultiplierBonus
    ): IntegerObject
    {
        return $baseOfWoundsModifier->sub($agilityMultiplierBonus * $agility->getValue());
    }

    private function increaseByNegativeAgility(
        IntegerObject $baseOfWoundsModifier,
        Agility $agility
    ): IntegerObject
    {
        // yes, it INCREASES wounds (minus minus = plus) and yes, only by agility value itself, without multiplier
        return $baseOfWoundsModifier->sub($agility->getValue());
    }

    private function lowerByArmorMaximalProtection(
        int $armorMaximalProtection,
        IntegerObject $baseOfWoundsModifier
    ): IntegerObject
    {
        return $baseOfWoundsModifier->sub($armorMaximalProtection);
    }

    private function lowerByArmor(
        PositiveInteger $armorProtection,
        IntegerObject $baseOfWoundsModifier
    ): IntegerObject
    {
        return $baseOfWoundsModifier->sub($armorProtection);
    }
}