<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Body\FatigueByLoad;

use DrdPlus\Tables\Body\MovementTypes\MovementTypesTable;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Properties\AthleticsInterface;
use Granam\Integer\Tools\ToInteger;

/**
 * See PPH page 114 right column, @link https://pph.drdplus.info/#tabulka_unavy_za_nalozen
 */
class FatigueByLoadTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/fatigue_by_load.csv';
    }

    public const MISSING_STRENGTH_UP_TO = 'missing_strength_up_to';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return ['missing_strength_up_to'];
    }

    public const LOAD = 'load';
    public const WEARIES_LIKE = 'wearies_like';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::LOAD => self::STRING,
            self::WEARIES_LIKE => self::STRING,
        ];
    }

    /**
     * @param int $missingStrength
     * @param AthleticsInterface $athletics
     * @param MovementTypesTable $movementTypesTable
     * @return Time|false Gives false if there is no fatigue from current load at all
     * @throws \DrdPlus\Tables\Body\FatigueByLoad\Exceptions\OverloadedAndCanNotMove
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function getPeriodForPointOfFatigue($missingStrength, AthleticsInterface $athletics, MovementTypesTable $movementTypesTable)
    {
        $desiredRow = $this->getRowFittingToMissingStrength($missingStrength, $athletics);

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $movementTypesTable->getPeriodForPointOfFatigueOn($desiredRow[self::WEARIES_LIKE]);
    }

    /**
     * @param int $missingStrength
     * @param AthleticsInterface $athletics
     * @return array
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \DrdPlus\Tables\Body\FatigueByLoad\Exceptions\OverloadedAndCanNotMove
     */
    private function getRowFittingToMissingStrength($missingStrength, AthleticsInterface $athletics): array
    {
        $missingStrength = ToInteger::toInteger($missingStrength) - $athletics->getAthleticsBonus()->getValue();
        $usedMaximalMissingStrength = false;
        $desiredRow = [];
        foreach ($this->getIndexedValues() as $maximalMissingStrength => $row) {
            if ($maximalMissingStrength >= $missingStrength
                && ($usedMaximalMissingStrength === false || $usedMaximalMissingStrength > $maximalMissingStrength)
            ) {
                $desiredRow = $row;
                $usedMaximalMissingStrength = $maximalMissingStrength;
            }
        }
        if (!$desiredRow) { // overload is so big so person can not move
            throw new Exceptions\OverloadedAndCanNotMove(
                "Missing strength {$missingStrength} causes overload so the being can not move at all"
                . ($athletics->getAthleticsBonus()->getValue() > 0
                    ? " even with athletics {$athletics->getAthleticsBonus()}"
                    : ''
                )
            );
        }

        return $desiredRow;
    }

    /**
     * @param int $missingStrength
     * @param AthleticsInterface $athletics
     * @return string
     * @throws \DrdPlus\Tables\Body\FatigueByLoad\Exceptions\OverloadedAndCanNotMove
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function getLoadName(int $missingStrength, AthleticsInterface $athletics): string
    {
        $desiredRow = $this->getRowFittingToMissingStrength($missingStrength, $athletics);

        return $desiredRow[self::LOAD];
    }
}