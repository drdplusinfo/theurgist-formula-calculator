<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Combat\Attacks\Partials;

use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Partials\AbstractFileTable;
use Granam\Float\Tools\ToFloat;

abstract class AbstractAttackNumberByDistanceTable extends AbstractFileTable
{

    public const DISTANCE_BONUS = 'distance_bonus';
    public const RANGED_ATTACK_NUMBER_MODIFIER = 'ranged_attack_number_modifier';

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::DISTANCE_BONUS => self::INTEGER,
            self::RANGED_ATTACK_NUMBER_MODIFIER => self::INTEGER,
        ];
    }

    /**
     * @param Distance $distance
     * @return int
     */
    abstract public function getAttackNumberModifierByDistance(Distance $distance): int;

    /**
     * Values may be already ordered from file, but have to be sure.
     *
     * @return array
     */
    protected function getOrderedByDistanceAsc(): array
    {
        $values = $this->getIndexedValues();
        uksort($values, function ($oneDistanceInMeters, $anotherDistanceInMeters) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $oneDistanceInMeters = ToFloat::toPositiveFloat($oneDistanceInMeters);
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $anotherDistanceInMeters = ToFloat::toPositiveFloat($anotherDistanceInMeters);

            return $oneDistanceInMeters <=> $anotherDistanceInMeters; // lowest first
        });

        return $values;
    }
}