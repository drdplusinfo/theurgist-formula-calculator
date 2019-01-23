<?php
declare(strict_types = 1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;

/**
 * @method EpicenterShift getWithAddition($additionValue)
 */
class EpicenterShift extends CastingParameter
{
    /**
     * @var Distance
     */
    private $distance;

    /**
     * @param array $values
     * @param Distance|null $distance to provide more accurate distance
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\EpicenterShiftDistanceDoesNotMatch
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\InvalidValueForCastingParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\MissingValueForFormulaDifficultyAddition
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfDifficultyIncrement
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyValue
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyNotation
     */
    public function __construct(array $values, Distance $distance = null)
    {
        parent::__construct($values);
        if ($distance !== null) {
            if ($distance->getBonus()->getValue() !== $this->getValue()) {
                throw new Exceptions\EpicenterShiftDistanceDoesNotMatch(
                    'Expected distance of epicenter shift with bonus ' . $this->getValue()
                    . ', got distance with bonus ' . $distance->getBonus()->getValue()
                );
            }
            $this->distance = $distance;
        }
    }

    /**
     * @param DistanceTable $distanceTable
     * @return Distance
     */
    public function getDistance(DistanceTable $distanceTable): Distance
    {
        if ($this->distance === null) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $this->distance = (new DistanceBonus($this->getValue(), $distanceTable))->getDistance();
        }

        return $this->distance;
    }
}