<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Combat\Attacks;

use DrdPlus\Tables\Combat\Attacks\Partials\AbstractAttackNumberByDistanceTable;
use DrdPlus\Tables\Measurements\Distance\Distance;
use Granam\Float\Tools\ToFloat;

/**
 * Data are calculated as -((Distance bonus / 2) - 9),
 * see PPH page 104, @link https://pph.drdplus.info/#tabulka_oprav_za_vzdalenost
 */
class AttackNumberByContinuousDistanceTable extends AbstractAttackNumberByDistanceTable
{
    public const DISTANCE_WITH_NO_IMPACT_TO_ATTACK_NUMBER = 8; // meters

    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/attack_number_by_continuous_distance.csv';
    }

    public const DISTANCE_IN_METERS = 'distance_in_meters';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::DISTANCE_IN_METERS];
    }

    /**
     * @param Distance $distance
     * @return int
     * @throws \DrdPlus\Tables\Combat\Attacks\Exceptions\DistanceOutOfKnownValues
     */
    public function getAttackNumberModifierByDistance(Distance $distance): int
    {
        $distanceInMeters = $distance->getMeters();
        $orderedByDistanceAsc = $this->getOrderedByDistanceAsc();
        foreach ($orderedByDistanceAsc as $distanceInMetersUpTo => $row) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            if ($distanceInMeters <= ToFloat::toPositiveFloat($distanceInMetersUpTo)) { // including
                return $row[self::RANGED_ATTACK_NUMBER_MODIFIER];
            }
        }

        throw new Exceptions\DistanceOutOfKnownValues(
            "Given distance {$distance} is so far so we do not have values for it"
        );
    }
}