<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Combat\Attacks;

use DrdPlus\Tables\Combat\Attacks\Partials\AbstractAttackNumberByDistanceTable;
use DrdPlus\Tables\Measurements\Distance\Distance;
use Granam\Float\Tools\ToFloat;

/**
 * See PPH page 104 left column bottom, @link https://pph.drdplus.info/#tabulka_oprav_za_vzdalenost
 */
class AttackNumberByDistanceTable extends AbstractAttackNumberByDistanceTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/attack_number_by_distance.csv';
    }

    public const DISTANCE_IN_METERS_FROM = 'distance_in_meters_from';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::DISTANCE_IN_METERS_FROM];
    }

    /**
     * @param Distance $distance
     * @return int
     */
    public function getAttackNumberModifierByDistance(Distance $distance): int
    {
        $distanceInMeters = $distance->getMeters();
        $orderedByDistanceDesc = $this->getOrderedByDistanceAsc();
        $attackNumberModifierCandidate = 0;
        foreach ($orderedByDistanceDesc as $distanceInMetersFrom => $row) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            if ($distanceInMeters >= ToFloat::toPositiveFloat($distanceInMetersFrom)) {
                $attackNumberModifierCandidate = $row[self::RANGED_ATTACK_NUMBER_MODIFIER];
            }
        }

        return $attackNumberModifierCandidate;
    }
}