<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Riding;

use DrdPlus\Codes\Transport\RidingAnimalMovementCode;
use DrdPlus\Tables\Partials\AbstractFileTable;

/**
 * See PPH page 122 right column (table without name), @link https://pph.drdplus.info/#tabulka_jizdy_podle_druhu_pohybu
 */
class RidesByMovementTypeTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/rides.csv';
    }

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return ['movement_type'];
    }

    public const RIDE = 'ride';
    public const ADDITIONAL = 'additional';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [self::RIDE => self::INTEGER, self::ADDITIONAL => self::BOOLEAN];
    }

    /**
     * @param RidingAnimalMovementCode $ridingAnimalMovementCode
     * @param bool $isJumping
     * @return Ride
     */
    public function getRideFor(RidingAnimalMovementCode $ridingAnimalMovementCode, bool $isJumping): Ride
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Ride(
            $this->getValue([$ridingAnimalMovementCode->getValue()], self::RIDE)
            + ($isJumping
                ? $this->getValue([RidingAnimalMovementCode::JUMPING], self::RIDE)
                : 0)
        );
    }

}