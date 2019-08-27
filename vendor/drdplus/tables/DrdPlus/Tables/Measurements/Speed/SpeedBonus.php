<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Speed;

use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Tables;

class SpeedBonus extends AbstractBonus
{
    /**
     * @param $bonusValue
     * @param Tables $tables
     * @return SpeedBonus
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public static function getIt($bonusValue, Tables $tables): SpeedBonus
    {
        return new static($bonusValue, $tables->getSpeedTable());
    }

    /**
     * @var SpeedTable
     */
    private $speedTable;

    /**
     * @param int $bonusValue
     * @param SpeedTable $speedTable
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public function __construct($bonusValue, SpeedTable $speedTable)
    {
        parent::__construct($bonusValue);
        $this->speedTable = $speedTable;
    }

    /**
     * @param string|null $wantedUnit
     * @return Speed
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    public function getSpeed(string $wantedUnit = null): Speed
    {
        return $this->speedTable->toSpeed($this, $wantedUnit);
    }

    /**
     * @param DistanceTable $distanceTable
     * @return \DrdPlus\Tables\Measurements\Distance\Distance
     */
    public function getDistancePerRound(DistanceTable $distanceTable): Distance
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return (new DistanceBonus($this->getValue(), $distanceTable))->getDistance();
    }

}