<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Riding;

use DrdPlus\Codes\Transport\RidingAnimalCode;
use DrdPlus\Codes\Transport\RidingAnimalPropertyCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Calculations\SumAndRound;

/**
 * See PPH page 122 left column, @link https://pph.drdplus.info/#tabulka_jezdeckych_zvirat
 */
class RidingAnimalsTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/riding_animals.csv';
    }

    public const ANIMAL = 'animal';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::ANIMAL];
    }

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            RidingAnimalPropertyCode::SPEED => self::INTEGER,
            RidingAnimalPropertyCode::ENDURANCE => self::INTEGER,
            RidingAnimalPropertyCode::MAXIMAL_LOAD => self::INTEGER,
            RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => self::INTEGER,
            RidingAnimalPropertyCode::DEFIANCE => self::INTEGER,
        ];
    }

    /**
     * @param RidingAnimalCode $ridingAnimalCode
     * @return int
     */
    public function getSpeed(RidingAnimalCode $ridingAnimalCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue([$ridingAnimalCode->getValue()], RidingAnimalPropertyCode::SPEED);
    }

    /**
     * @param RidingAnimalCode $ridingAnimalCode
     * @return int
     */
    public function getEndurance(RidingAnimalCode $ridingAnimalCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue([$ridingAnimalCode->getValue()], RidingAnimalPropertyCode::ENDURANCE);
    }

    /**
     * @param RidingAnimalCode $ridingAnimalCode
     * @return int
     */
    public function getMaximalLoad(RidingAnimalCode $ridingAnimalCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue([$ridingAnimalCode->getValue()], RidingAnimalPropertyCode::MAXIMAL_LOAD);
    }

    /**
     * @param RidingAnimalCode $ridingAnimalCode
     * @return int
     */
    public function getMaximalLoadInKg(RidingAnimalCode $ridingAnimalCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue([$ridingAnimalCode->getValue()], RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG);
    }

    /**
     * @param RidingAnimalCode $ridingAnimalCode
     * @param bool $isJumpingOrDoesDangerousMoves
     * @return int
     */
    public function getDefianceOfDomesticated(RidingAnimalCode $ridingAnimalCode, bool $isJumpingOrDoesDangerousMoves): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return
            $this->getValue([$ridingAnimalCode->getValue()], RidingAnimalPropertyCode::DEFIANCE)
            + ($isJumpingOrDoesDangerousMoves ? 2 : 0);
    }

    /**
     * @param RidingAnimalCode $ridingAnimalCode
     * @param DefianceOfWildPercents $defianceOfWildPercents
     * @param bool $isJumpingOrDoesDangerousMoves
     * @return int
     */
    public function getDefianceOfWild(
        RidingAnimalCode $ridingAnimalCode,
        DefianceOfWildPercents $defianceOfWildPercents,
        bool $isJumpingOrDoesDangerousMoves
    ): int
    {
        $defianceOfDomesticated = $this->getDefianceOfDomesticated($ridingAnimalCode, $isJumpingOrDoesDangerousMoves);
        $additionForWild = SumAndRound::round(10 + (2 * $defianceOfWildPercents->getRate())); // 10..12

        return $defianceOfDomesticated + $additionForWild;
    }

}