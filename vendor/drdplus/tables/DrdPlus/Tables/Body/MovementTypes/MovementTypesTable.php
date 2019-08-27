<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Body\MovementTypes;

use DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode;
use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Codes\Transport\MovementTypeCode;
use DrdPlus\Tables\Measurements\Fatigue\Fatigue;
use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Measurements\Speed\SpeedTable;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use DrdPlus\Tables\Properties\EnduranceInterface;
use DrdPlus\Tables\Tables;
use Granam\String\StringInterface;
use Granam\Tools\ValueDescriber;
use DrdPlus\Calculations\SumAndRound;

/**
 * See PPH page 112 right column, @link https://pph.drdplus.info/#tabulka_druhu_pohybu
 */
class MovementTypesTable extends AbstractFileTable
{
    /** @var SpeedTable */
    private $speedTable;
    /** @var TimeTable */
    private $timeTable;

    /**
     * @param SpeedTable $speedTable
     * @param TimeTable $timeTable
     */
    public function __construct(SpeedTable $speedTable, TimeTable $timeTable)
    {
        $this->speedTable = $speedTable;
        $this->timeTable = $timeTable;
    }

    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/movement_types.csv';
    }

    public const MOVEMENT_TYPE = 'movement_type';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::MOVEMENT_TYPE,];
    }

    public const BONUS_TO_MOVEMENT_SPEED = 'bonus_to_movement_speed';
    public const HOURS_PER_POINT_OF_FATIGUE = 'hours_per_point_of_fatigue';
    public const MINUTES_PER_POINT_OF_FATIGUE = 'minutes_per_point_of_fatigue';
    public const ROUNDS_PER_POINT_OF_FATIGUE = 'rounds_per_point_of_fatigue';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::BONUS_TO_MOVEMENT_SPEED => self::INTEGER,
            self::HOURS_PER_POINT_OF_FATIGUE => self::FLOAT,
            self::MINUTES_PER_POINT_OF_FATIGUE => self::FLOAT,
            self::ROUNDS_PER_POINT_OF_FATIGUE => self::FLOAT,
        ];
    }

    public const WAITING = MovementTypeCode::WAITING;
    public const WALK = MovementTypeCode::WALK;
    public const RUSH = MovementTypeCode::RUSH;
    public const RUN = MovementTypeCode::RUN;
    public const SPRINT = MovementTypeCode::SPRINT;

    /**
     * @param string|MovementTypeCode $movementTypeCode
     * @return SpeedBonus
     * @throws \DrdPlus\Tables\Body\MovementTypes\Exceptions\UnknownMovementType
     */
    public function getSpeedBonus($movementTypeCode): SpeedBonus
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return new SpeedBonus(
                $this->getValue([$movementTypeCode], self::BONUS_TO_MOVEMENT_SPEED),
                $this->speedTable
            );
        } catch (RequiredRowNotFound $requiredRowDataNotFound) {
            throw new Exceptions\UnknownMovementType(
                'Given movement type is not known ' . ValueDescriber::describe($movementTypeCode)
            );
        }
    }

    /**
     * @return SpeedBonus
     */
    public function getSpeedBonusOnWaiting(): SpeedBonus
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getSpeedBonus(self::WAITING);
    }

    /**
     * @return SpeedBonus
     */
    public function getSpeedBonusOnWalk(): SpeedBonus
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getSpeedBonus(self::WALK);
    }

    /**
     * @return SpeedBonus
     */
    public function getSpeedBonusOnRush(): SpeedBonus
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getSpeedBonus(self::RUSH);
    }

    /**
     * @return SpeedBonus
     */
    public function getSpeedBonusOnRun(): SpeedBonus
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getSpeedBonus(self::RUN);
    }

    /**
     * @return SpeedBonus
     */
    public function getSpeedBonusOnSprint(): SpeedBonus
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getSpeedBonus(self::SPRINT);
    }

    /**
     * @param string|StringInterface $movementType
     * @return Time|false
     * @throws \DrdPlus\Tables\Body\MovementTypes\Exceptions\UnknownMovementType
     */
    public function getPeriodForPointOfFatigueOn($movementType)
    {
        try {
            $movementTypeCode = MovementTypeCode::getIt($movementType);
        } catch (UnknownValueForCode $unknownValueForCode) {
            throw new Exceptions\UnknownMovementType(
                'Given movement type ' . ValueDescriber::describe($movementType) . ' is not known'
            );
        }
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $hours = $this->getValue([$movementTypeCode], self::HOURS_PER_POINT_OF_FATIGUE);
        if ($hours !== false) {
            return new Time($hours, TimeUnitCode::HOUR, $this->timeTable);
        }
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $minutes = $this->getValue([$movementTypeCode], self::MINUTES_PER_POINT_OF_FATIGUE);
        if ($minutes !== false) {
            return new Time($minutes, TimeUnitCode::MINUTE, $this->timeTable);
        }
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $rounds = $this->getValue([$movementTypeCode], self::ROUNDS_PER_POINT_OF_FATIGUE);
        if ($rounds !== false) {
            return new Time($rounds, TimeUnitCode::ROUND, $this->timeTable);
        }

        return false;
    }

    /**
     * @return Time
     */
    public function getPeriodForPointOfFatigueOnWalk(): Time
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getPeriodForPointOfFatigueOn(MovementTypeCode::WALK);
    }

    /**
     * @return Time
     */
    public function getPeriodForPointOfFatigueOnRush(): Time
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getPeriodForPointOfFatigueOn(MovementTypeCode::RUSH);
    }

    /**
     * @return Time
     */
    public function getPeriodForPointOfFatigueOnRun(): Time
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getPeriodForPointOfFatigueOn(MovementTypeCode::RUN);
    }

    /**
     * @return Time
     */
    public function getPeriodForPointOfFatigueOnSprint(): Time
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getPeriodForPointOfFatigueOn(MovementTypeCode::SPRINT);
    }

    /**
     * @param Time $timeOfWalk
     * @param Tables $tables
     * @return Fatigue
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     */
    public function getFatigueOnWalk(Time $timeOfWalk, Tables $tables): Fatigue
    {
        return $this->getFatigueOn(MovementTypeCode::getIt(MovementTypeCode::WALK), $timeOfWalk, $tables);
    }

    /**
     * @param MovementTypeCode $movementType
     * @param Time $timeOfMove
     * @param Tables $tables
     * @return Fatigue
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     */
    public function getFatigueOn(MovementTypeCode $movementType, Time $timeOfMove, Tables $tables): Fatigue
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $periodOnWalk = $this->getPeriodForPointOfFatigueOn($movementType)->getInUnit($timeOfMove->getUnit());
        $fatigueValue = SumAndRound::round($timeOfMove->getValue() / $periodOnWalk->getValue());

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Fatigue($fatigueValue, $tables->getFatigueTable());
    }

    /**
     * @param Time $timeOfRush
     * @param Tables $tables
     * @return Fatigue
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     */
    public function getFatigueOnRush(Time $timeOfRush, Tables $tables): Fatigue
    {
        return $this->getFatigueOn(MovementTypeCode::getIt(MovementTypeCode::RUSH), $timeOfRush, $tables);
    }

    /**
     * @param Time $timeOfRun
     * @param Tables $tables
     * @return Fatigue
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     */
    public function getFatigueOnRun(Time $timeOfRun, Tables $tables): Fatigue
    {
        return $this->getFatigueOn(MovementTypeCode::getIt(MovementTypeCode::RUN), $timeOfRun, $tables);
    }

    /**
     * @param Time $timeOfSprint
     * @param Tables $tables
     * @return Fatigue
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     */
    public function getFatigueOnSprint(Time $timeOfSprint, Tables $tables): Fatigue
    {
        return $this->getFatigueOn(MovementTypeCode::getIt(MovementTypeCode::SPRINT), $timeOfSprint, $tables);
    }

    /**
     * @param EnduranceInterface $endurance
     * @return TimeBonus
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertThatBonusToTime
     */
    public function getMaximumTimeBonusToSprint(EnduranceInterface $endurance): TimeBonus
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new TimeBonus($endurance->getValue(), $this->timeTable);
    }

    /**
     * @param EnduranceInterface $endurance
     * @return TimeBonus
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertThatBonusToTime
     */
    public function getRequiredTimeBonusToWalkAfterFullSprint(EnduranceInterface $endurance): TimeBonus
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new TimeBonus($endurance->getValue() + 20, $this->timeTable);
    }

}