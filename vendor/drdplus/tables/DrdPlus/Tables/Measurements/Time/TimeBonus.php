<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Measurements\Time;

use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Tables;

class TimeBonus extends AbstractBonus
{
    /**
     * @param int|\Granam\Integer\IntegerInterface $bonusValue
     * @param Tables $tables
     * @return TimeBonus
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public static function getIt($bonusValue, Tables $tables): TimeBonus
    {
        return new static($bonusValue, $tables->getTimeTable());
    }

    /**
     * @var TimeTable
     */
    private $timeTable;

    /**
     * @param int|\Granam\Integer\IntegerInterface $value
     * @param TimeTable $timeTable
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public function __construct($value, TimeTable $timeTable)
    {
        parent::__construct($value);
        $this->timeTable = $timeTable;
    }

    /**
     * @param string|\Granam\String\StringInterface|null $wantedUnit
     * @return Time|null
     */
    public function findTime($wantedUnit = null): ?Time
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->timeTable->hasTimeFor($this, $wantedUnit)
            ? $this->timeTable->toTime($this, $wantedUnit)
            : null;
    }

    /**
     * @param string|\Granam\String\StringInterface|null $wantedUnit
     * @return Time
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertThatBonusToTime
     */
    public function getTime($wantedUnit = null): Time
    {
        $time = $this->findTime($wantedUnit);
        if ($time !== null) {
            return $time;
        }
        throw new Exceptions\CanNotConvertThatBonusToTime(
            'Can not convert time bonus ' . $this->getValue() . ' into time with unit '
            . ($wantedUnit ?? '"any possible"')
        );
    }

}