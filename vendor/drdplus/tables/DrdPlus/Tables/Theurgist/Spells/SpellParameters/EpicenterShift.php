<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Tables;
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
     * @var DistanceBonus
     */
    private $distanceBonus;

    /**
     * @param array $values
     * @param Tables $tables
     * @param Distance|null $distance to provide more accurate distance
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\EpicenterShiftDistanceDoesNotMatch
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\InvalidValueForCastingParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\MissingValueForFormulaDifficultyAddition
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfDifficultyIncrement
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyValue
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyNotation
     */
    public function __construct(array $values, Tables $tables, Distance $distance = null)
    {
        parent::__construct($values, $tables);
        if ($distance !== null) {
            if ($distance->getBonus()->getValue() !== $this->getValue()) {
                throw new Exceptions\EpicenterShiftDistanceDoesNotMatch(
                    'Expected distance of epicenter shift with bonus ' . $this->getValue()
                    . ', got distance with bonus ' . $distance->getBonus()->getValue()
                );
            }
            $this->distance = $distance;
            $this->distanceBonus = $distance->getBonus();
        }
    }

    public function getDistance(): Distance
    {
        if ($this->distance !== null) {
            return $this->distance;
        }
        return $this->getDistanceBonus()->getDistance();
    }

    public function getDistanceBonus(): DistanceBonus
    {
        if ($this->distanceBonus === null) {
            $this->distanceBonus = new DistanceBonus($this->getValue(), $this->getTables()->getDistanceTable());
        }
        return $this->distanceBonus;
    }
}