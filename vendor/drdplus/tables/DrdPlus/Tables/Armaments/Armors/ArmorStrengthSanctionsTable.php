<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Armaments\Armors;

use DrdPlus\Tables\Armaments\Exceptions\CanNotUseArmorBecauseOfMissingStrength;
use DrdPlus\Tables\Armaments\Partials\AbstractStrengthSanctionsTable;

/**
 * See PPH page 91 right column, @link https://pph.drdplus.info/#tabulka_postihu_za_zbroj
 */
class ArmorStrengthSanctionsTable extends AbstractStrengthSanctionsTable
{
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/armor_strength_sanctions.csv';
    }

    public const SANCTION_DESCRIPTION = 'sanction_description';
    public const AGILITY_SANCTION = 'agility_sanction';
    public const CAN_MOVE = 'can_move';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::MISSING_STRENGTH => self::POSITIVE_INTEGER,
            self::SANCTION_DESCRIPTION => self::STRING,
            self::AGILITY_SANCTION => self::NEGATIVE_INTEGER,
            self::CAN_MOVE => self::BOOLEAN,
        ];
    }

    /**
     * @param int $missingStrength
     * @return bool
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function canUseIt(int $missingStrength): bool
    {
        return $this->canMove($missingStrength);
    }

    /**
     * @param int $missingStrength
     * @return bool
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function canMove(int $missingStrength): bool
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getSanctionOf(
            $missingStrength,
            self::CAN_MOVE,
            false /* do not check missing strength before value determination */
        );
    }

    /**
     * @param int $missingStrength
     * @return string
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotUseArmorBecauseOfMissingStrength
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function getSanctionDescription(int $missingStrength): string
    {
        return $this->getSanctionOf($missingStrength, self::SANCTION_DESCRIPTION, false /* do not check maximal missing strength */);
    }

    /**
     * @param int $missingStrength
     * @param string $columnName
     * @param bool $guardMaximumMissingStrength
     * @return int|bool|string
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotUseArmorBecauseOfMissingStrength
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    private function getSanctionOf(int $missingStrength, string $columnName, $guardMaximumMissingStrength = true)
    {
        if ($guardMaximumMissingStrength && !$this->canMove($missingStrength)) {
            throw new CanNotUseArmorBecauseOfMissingStrength(
                "Too much missing strength {$missingStrength} to bear an armor"
            );
        }

        return $this->getSanctionsForMissingStrength($missingStrength)[$columnName];
    }

    /**
     * @param int $missingStrength
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotUseArmorBecauseOfMissingStrength
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function getAgilityMalus(int $missingStrength): int
    {
        return $this->getSanctionOf($missingStrength, self::AGILITY_SANCTION);
    }
}