<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Environments;

use DrdPlus\Codes\Environment\LandingSurfaceCode;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Tables\Partials\AbstractFileTable;
use Granam\Integer\IntegerWithHistory;
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
     * @return IntegerWithHistory
     */
    public function getBaseOfWoundsModifier(
        LandingSurfaceCode $landingSurfaceCode,
        Agility $agility,
        PositiveInteger $armorProtection
    ): IntegerWithHistory
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

    private function createBaseOfWoundsModifier(array $row): IntegerWithHistory
    {
        return new IntegerWithHistory($row[self::POWER_OF_WOUND_MODIFIER]);
    }

    private function lowerByPositiveAgility(
        IntegerWithHistory $baseOfWoundsModifier,
        Agility $agility,
        int $agilityMultiplierBonus
    ): IntegerWithHistory
    {
        return $baseOfWoundsModifier->sub($agilityMultiplierBonus * $agility->getValue());
    }

    private function increaseByNegativeAgility(
        IntegerWithHistory $baseOfWoundsModifier,
        Agility $agility
    ): IntegerWithHistory
    {
        // yes, it INCREASES wounds (minus minus = plus) and yes, only by agility value itself, without multiplier
        return $baseOfWoundsModifier->sub($agility->getValue());
    }

    private function lowerByArmorMaximalProtection(
        int $armorMaximalProtection,
        IntegerWithHistory $baseOfWoundsModifier
    ): IntegerWithHistory
    {
        return $baseOfWoundsModifier->sub($armorMaximalProtection);
    }

    private function lowerByArmor(
        PositiveInteger $armorProtection,
        IntegerWithHistory $baseOfWoundsModifier
    ): IntegerWithHistory
    {
        return $baseOfWoundsModifier->sub($armorProtection);
    }
}