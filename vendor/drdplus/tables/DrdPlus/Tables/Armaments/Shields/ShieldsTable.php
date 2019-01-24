<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Armaments\Shields;

use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Tables\Armaments\Exceptions\UnknownShield;
use DrdPlus\Tables\Armaments\Partials\AbstractArmamentsTable;
use DrdPlus\Tables\Armaments\Partials\MeleeWeaponlikesTable;
use DrdPlus\Tables\Armaments\Partials\UnwieldyTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use Granam\String\StringInterface;
use Granam\Tools\ValueDescriber;

/**
 * See PPH page 86 right column, @link https://pph.drdplus.info/#tabulka_stitu
 */
class ShieldsTable extends AbstractArmamentsTable implements UnwieldyTable, MeleeWeaponlikesTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/shields.csv';
    }

    public const SHIELD = 'shield';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::SHIELD];
    }

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::REQUIRED_STRENGTH => self::INTEGER,
            self::LENGTH => self::INTEGER,
            self::RESTRICTION => self::INTEGER,
            self::OFFENSIVENESS => self::INTEGER,
            self::WOUNDS => self::INTEGER,
            self::WOUNDS_TYPE => self::STRING,
            self::COVER => self::INTEGER,
            self::WEIGHT => self::FLOAT,
            self::TWO_HANDED_ONLY => self::BOOLEAN,
        ];
    }

    /**
     * @param string|StringInterface $shieldCode
     * @return int|false
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownShield
     */
    public function getRequiredStrengthOf($shieldCode)
    {
        return $this->getValueOf($shieldCode, self::REQUIRED_STRENGTH);
    }

    /**
     * @param string|StringInterface $shieldCode
     * @param string $valueName
     * @return int|float|string|bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownShield
     */
    private function getValueOf($shieldCode, string $valueName)
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue([$shieldCode], $valueName);
        } catch (RequiredRowNotFound $exception) {
            throw new UnknownShield(
                'Unknown shield code ' . ValueDescriber::describe($shieldCode)
            );
        }
    }

    /**
     * @param string|StringInterface $weaponlikeCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownShield
     */
    public function getLengthOf($weaponlikeCode): int
    {
        return $this->getValueOf($weaponlikeCode, self::LENGTH);
    }

    /**
     * @param string|StringInterface $shieldCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownShield
     */
    public function getRestrictionOf($shieldCode): int
    {
        return $this->getValueOf($shieldCode, self::RESTRICTION);
    }

    /**
     * @param string|StringInterface $shieldCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownShield
     */
    public function getOffensivenessOf($shieldCode): int
    {
        return $this->getValueOf($shieldCode, self::OFFENSIVENESS);
    }

    /**
     * @param string|StringInterface $shieldCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownShield
     */
    public function getWoundsOf($shieldCode): int
    {
        return $this->getValueOf($shieldCode, self::WOUNDS);
    }

    /**
     * @param string|StringInterface $shieldCode
     * @return string
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownShield
     */
    public function getWoundsTypeOf($shieldCode): string
    {
        return $this->getValueOf($shieldCode, self::WOUNDS_TYPE);
    }

    /**
     * @param string|StringInterface $shieldCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownShield
     */
    public function getCoverOf($shieldCode): int
    {
        return $this->getValueOf($shieldCode, self::COVER);
    }

    /**
     * @param string|StringInterface $shieldCode
     * @return float
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownShield
     */
    public function getWeightOf($shieldCode): float
    {
        return $this->getValueOf($shieldCode, self::WEIGHT);
    }

    /**
     * @param string|ShieldCode $shieldCode
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownShield
     */
    public function getTwoHandedOnlyOf($shieldCode): bool
    {
        return $this->getValueOf($shieldCode, self::TWO_HANDED_ONLY);
    }

}