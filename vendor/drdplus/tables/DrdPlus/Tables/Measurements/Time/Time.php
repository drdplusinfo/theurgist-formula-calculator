<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Time;

use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Tables\Measurements\MeasurementWithBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractMeasurement;
use Granam\Integer\Tools\ToInteger;
use Granam\Scalar\Tools\ToString;
use Granam\String\StringInterface;
use Granam\Tools\ValueDescriber;

class Time extends AbstractMeasurement implements MeasurementWithBonus
{
    public const HOURS_PER_DAY = 12.0;

    public const ROUND = TimeUnitCode::ROUND;
    public const MINUTE = TimeUnitCode::MINUTE;
    public const HOUR = TimeUnitCode::HOUR;
    public const DAY = TimeUnitCode::DAY;
    public const MONTH = TimeUnitCode::MONTH;
    public const YEAR = TimeUnitCode::YEAR;

    /**
     * @var TimeTable
     */
    private $timeTable;

    /**
     * @param float $value
     * @param TimeTable $timeTable
     * @param string $unit
     */
    public function __construct($value, $unit, TimeTable $timeTable)
    {
        $this->timeTable = $timeTable;
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::__construct($value, $unit);
    }

    /**
     * @return float|int
     */
    public function getValue()
    {
        if ($this->getUnit() === TimeUnitCode::ROUND) {
            // only rounds are always integer
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return ToInteger::toInteger(parent::getValue());
        }

        return parent::getValue();
    }

    /**
     * @return TimeUnitCode
     */
    public function getUnitCode(): TimeUnitCode
    {
        return TimeUnitCode::getIt($this->getUnit());
    }

    /**
     * @return string[]
     */
    public function getPossibleUnits(): array
    {
        return [TimeUnitCode::ROUND, TimeUnitCode::MINUTE, TimeUnitCode::HOUR, TimeUnitCode::DAY, TimeUnitCode::MONTH, TimeUnitCode::YEAR];
    }

    /**
     * @return TimeBonus
     */
    public function getBonus(): TimeBonus
    {
        return $this->timeTable->toBonus($this);
    }

    /**
     * @return Time|null
     */
    public function findRounds(): ?Time
    {
        return $this->findInUnit(TimeUnitCode::ROUND);
    }

    /**
     * @return Time
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     */
    public function getRounds(): Time
    {
        return $this->getInUnit(TimeUnitCode::ROUND);
    }

    /**
     * @param string|StringInterface $unit
     * @return Time
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     */
    public function getInUnit($unit): Time
    {
        $inDifferentUnit = $this->findInUnit($unit);
        if ($inDifferentUnit !== null) {
            return $inDifferentUnit;
        }
        throw new Exceptions\CanNotConvertTimeToRequiredUnit(
            'Can not convert ' . $this->getValue() . ' ' . $this->getUnit() . '(s)'
            . ' into ' . ValueDescriber::describe($unit)
        );
    }

    /**
     * @param string|StringInterface $unit
     * @return Time|null
     */
    public function findInUnit($unit): ?Time
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        if (ToString::toString($unit) === $this->getUnit()) {
            return clone $this;
        }
        $bonus = $this->timeTable->toBonus($this);

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->timeTable->hasTimeFor($bonus, $unit)
            ? $this->timeTable->toTime($bonus, $unit)
            : null;
    }

    /**
     * @return Time|null
     */
    public function findMinutes(): ?Time
    {
        return $this->findInUnit(TimeUnitCode::MINUTE);
    }

    /**
     * @return Time
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     */
    public function getMinutes(): Time
    {
        return $this->getInUnit(TimeUnitCode::MINUTE);
    }

    /**
     * @return Time|null
     */
    public function findHours(): ?Time
    {
        return $this->findInUnit(TimeUnitCode::HOUR);
    }

    /**
     * @return Time
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     */
    public function getHours(): Time
    {
        return $this->getInUnit(TimeUnitCode::HOUR);
    }

    /**
     * @return Time|null
     */
    public function findDays(): ?Time
    {
        return $this->findInUnit(TimeUnitCode::DAY);
    }

    /**
     * @return Time
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     */
    public function getDays(): Time
    {
        return $this->getInUnit(TimeUnitCode::DAY);
    }

    /**
     * @return Time|null
     */
    public function findMonths(): ?Time
    {
        return $this->findInUnit(TimeUnitCode::MONTH);
    }

    /**
     * @return Time
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     */
    public function getMonths(): Time
    {
        return $this->getInUnit(TimeUnitCode::MONTH);
    }

    /**
     * @return Time|null
     */
    public function findYears(): ?Time
    {
        return $this->findInUnit(TimeUnitCode::YEAR);
    }

    /**
     * @return Time
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     */
    public function getYears(): Time
    {
        return $this->getInUnit(TimeUnitCode::YEAR);
    }

    /**
     * @return Time|null
     */
    public function findInLesserUnit(): ?Time
    {
        $lesserUnit = $this->findLesserUnitThan($this->getUnit());
        if ($lesserUnit === null) {
            return null; // there is no lesser unit
        }

        return $this->findInUnit($lesserUnit);
    }

    /**
     * @param string $currentUnit
     * @return null|string
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\UnknownTimeUnit
     */
    private function findLesserUnitThan(string $currentUnit): ?string
    {
        $currentIndex = \array_search($currentUnit, TimeUnitCode::getPossibleValues(), true);
        if ($currentIndex === false) {
            throw new Exceptions\UnknownTimeUnit("Given time unit '{$currentUnit}' is not known");
        }
        if ($currentIndex === 0) {
            return null; // there is no lesser unit than current
        }

        return TimeUnitCode::getPossibleValues()[$currentIndex - 1];
    }
}