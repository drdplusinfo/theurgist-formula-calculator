<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Riding;

use DrdPlus\Codes\Transport\RidingAnimalMovementCode;
use DrdPlus\Tables\Body\MovementTypes\MovementTypesTable;
use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Measurements\Speed\SpeedTable;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Properties\EnduranceInterface;

/**
 * See PPH page 121 right column, @link https://pph.drdplus.info/#tabulka_druhu_pohybu_jezdeckych_zvirat_a_leteckych_nestvur
 */
class RidingAnimalsAndFlyingBeastsMovementTypesTable extends AbstractFileTable
{
    /**
     * @var SpeedTable
     */
    private $speedTable;
    /**
     * @var MovementTypesTable
     */
    private $movementTypesTable;

    /**
     * @param SpeedTable $speedTable
     * @param MovementTypesTable $movementTypesTable
     */
    public function __construct(SpeedTable $speedTable, MovementTypesTable $movementTypesTable)
    {
        $this->speedTable = $speedTable;
        $this->movementTypesTable = $movementTypesTable;
    }

    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/riding_animals_and_flying_beasts_movement_types.csv';
    }

    public const MOVEMENT_TYPE = 'movement_type';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::MOVEMENT_TYPE];
    }

    public const BONUS_TO_MOVEMENT_SPEED = MovementTypesTable::BONUS_TO_MOVEMENT_SPEED;
    public const FATIGUE_LIKE = 'fatigue_like';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::BONUS_TO_MOVEMENT_SPEED => self::INTEGER,
            self::FATIGUE_LIKE => self::STRING,
        ];
    }

    /**
     * @param RidingAnimalMovementCode $ridingAnimalMovementCode
     * @return SpeedBonus
     */
    public function getSpeedBonus(RidingAnimalMovementCode $ridingAnimalMovementCode): SpeedBonus
    {
        return new SpeedBonus(
            $this->getValue([$ridingAnimalMovementCode->getValue()], self::BONUS_TO_MOVEMENT_SPEED),
            $this->speedTable
        );
    }

    public const STILL = RidingAnimalMovementCode::STILL;
    public const GAIT = RidingAnimalMovementCode::GAIT;
    public const TROT = RidingAnimalMovementCode::TROT;
    public const CANTER = RidingAnimalMovementCode::CANTER;
    public const GALLOP = RidingAnimalMovementCode::GALLOP;

    /**
     * @return SpeedBonus
     */
    public function getSpeedBonusWhenStill(): SpeedBonus
    {
        return $this->getSpeedBonus(RidingAnimalMovementCode::getIt(self::STILL));
    }

    /**
     * @return SpeedBonus
     */
    public function getSpeedBonusOnGait(): SpeedBonus
    {
        return $this->getSpeedBonus(RidingAnimalMovementCode::getIt(RidingAnimalMovementCode::GAIT));
    }

    /**
     * @return SpeedBonus
     */
    public function getSpeedBonusOnTrot(): SpeedBonus
    {
        return $this->getSpeedBonus(RidingAnimalMovementCode::getIt(RidingAnimalMovementCode::TROT));
    }

    /**
     * @return SpeedBonus
     */
    public function getSpeedBonusOnCanter(): SpeedBonus
    {
        return $this->getSpeedBonus(RidingAnimalMovementCode::getIt(RidingAnimalMovementCode::CANTER));
    }

    /**
     * @return SpeedBonus
     */
    public function getSpeedBonusOnGallop(): SpeedBonus
    {
        return $this->getSpeedBonus(RidingAnimalMovementCode::getIt(RidingAnimalMovementCode::GALLOP));
    }

    /**
     * @param RidingAnimalMovementCode $ridingAnimalMovementCode
     * @return Time|false
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getPeriodForPointOfFatigue(RidingAnimalMovementCode $ridingAnimalMovementCode)
    {
        return $this->movementTypesTable->getPeriodForPointOfFatigueOn($this->getFatigueLike($ridingAnimalMovementCode));
    }

    /**
     * @param RidingAnimalMovementCode $ridingAnimalMovementCode
     * @return string
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    private function getFatigueLike(RidingAnimalMovementCode $ridingAnimalMovementCode): string
    {
        return $this->getValue([$ridingAnimalMovementCode->getValue()], self::FATIGUE_LIKE);
    }

    /**
     * @return Time
     */
    public function getPeriodForPointOfFatigueOnGait(): Time
    {
        return $this->getPeriodForPointOfFatigue(RidingAnimalMovementCode::getIt(RidingAnimalMovementCode::GAIT));
    }

    /**
     * @return Time
     */
    public function getPeriodForPointOfFatigueOnTrot(): Time
    {
        return $this->getPeriodForPointOfFatigue(RidingAnimalMovementCode::getIt(RidingAnimalMovementCode::TROT));
    }

    /**
     * @return Time
     */
    public function getPeriodForPointOfFatigueOnCanter(): Time
    {
        return $this->getPeriodForPointOfFatigue(RidingAnimalMovementCode::getIt(RidingAnimalMovementCode::CANTER));
    }

    /**
     * @return Time
     */
    public function getPeriodForPointOfFatigueOnGallop(): Time
    {
        return $this->getPeriodForPointOfFatigue(RidingAnimalMovementCode::getIt(RidingAnimalMovementCode::GALLOP));
    }

    /**
     * @param EnduranceInterface $endurance
     * @return TimeBonus
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertThatBonusToTime
     */
    public function getMaximumTimeBonusToGallop(EnduranceInterface $endurance): TimeBonus
    {
        return $this->movementTypesTable->getMaximumTimeBonusToSprint($endurance);
    }

    /**
     * @param EnduranceInterface $endurance
     * @return TimeBonus
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertThatBonusToTime
     */
    public function getRequiredTimeBonusToWalkAfterFullGallop(EnduranceInterface $endurance): TimeBonus
    {
        return $this->movementTypesTable->getRequiredTimeBonusToWalkAfterFullSprint($endurance);
    }

}