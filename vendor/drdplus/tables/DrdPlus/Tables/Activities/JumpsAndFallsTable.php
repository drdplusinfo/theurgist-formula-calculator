<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Activities;

use DrdPlus\Calculations\SumAndRound;
use DrdPlus\Codes\Environment\LandingSurfaceCode;
use DrdPlus\Codes\JumpMovementCode;
use DrdPlus\Codes\JumpTypeCode;
use DrdPlus\BaseProperties\Agility;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Weight\Weight;
use DrdPlus\Tables\Measurements\Wounds\Wounds;
use DrdPlus\Tables\Measurements\Wounds\WoundsBonus;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Properties\AthleticsInterface;
use DrdPlus\Tables\Properties\BodyWeightInterface;
use DrdPlus\Tables\Properties\SpeedInterface;
use DrdPlus\Tables\Tables;
use Granam\DiceRolls\Templates\Rolls\Roll1d6;
use Granam\Integer\PositiveInteger;

/**
 * See PPH page 119 right column, @link https://pph.drdplus.info/#tabulka_skoku
 */
class JumpsAndFallsTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/jumps_and_falls.csv';
    }

    /**
     * @return array
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            JumpMovementCode::STANDING_JUMP => self::INTEGER,
            JumpMovementCode::FLYING_JUMP => self::INTEGER,
        ];
    }

    public const JUMP_TYPE = 'jump_type';

    /**
     * @return array
     */
    protected function getRowsHeader(): array
    {
        return [
            self::JUMP_TYPE,
        ];
    }

    /**
     * @param JumpTypeCode $jumpTypeCode
     * @param Distance $ranDistance
     * @return int
     */
    public function getModifierToJump(JumpTypeCode $jumpTypeCode, Distance $ranDistance): int
    {
        return $this->getValue($jumpTypeCode, $this->getJumpMovementCodeByRanDistance($ranDistance));
    }

    /**
     * @param Distance $ranDistance
     * @return string
     */
    private function getJumpMovementCodeByRanDistance(Distance $ranDistance): string
    {
        if ($ranDistance->getMeters() >= 5) {
            return JumpMovementCode::FLYING_JUMP;
        }

        return JumpMovementCode::STANDING_JUMP;
    }

    /**
     * @param SpeedInterface $speed
     * @param AthleticsInterface $athletics
     * @param JumpTypeCode $jumpTypeCode
     * @param Distance $ranDistance
     * @param Roll1d6 $roll1D6
     * @return int
     */
    public function getJumpLength(
        SpeedInterface $speed,
        AthleticsInterface $athletics,
        JumpTypeCode $jumpTypeCode,
        Distance $ranDistance,
        Roll1d6 $roll1D6
    ): int
    {
        return $this->getModifierToJump($jumpTypeCode, $ranDistance)
            + SumAndRound::half($speed->getValue())
            + $athletics->getAthleticsBonus()->getValue()
            + $roll1D6->getValue();
    }

    /**
     * @param Distance $fallHeight
     * @param BodyWeightInterface $bodyWeight
     * @param Weight|null $weightOfItemsOverwhelmedBy
     * @param Roll1d6 $roll1D6
     * @param bool $itIsControlledJump
     * @param Agility $agility
     * @param AthleticsInterface $athletics
     * @param LandingSurfaceCode $landingSurfaceCode
     * @param PositiveInteger $bodyArmorProtection
     * @param bool $hitToHead
     * @param PositiveInteger $helmProtection
     * @param Tables $tables
     * @return Wounds
     */
    public function getWoundsFromJumpOrFall(
        Distance $fallHeight,
        BodyWeightInterface $bodyWeight,
        ?Weight $weightOfItemsOverwhelmedBy,
        Roll1d6 $roll1D6,
        bool $itIsControlledJump,
        Agility $agility,
        AthleticsInterface $athletics,
        LandingSurfaceCode $landingSurfaceCode,
        PositiveInteger $bodyArmorProtection,
        bool $hitToHead,
        PositiveInteger $helmProtection,
        Tables $tables
    ): Wounds
    {
        $meters = $fallHeight->getMeters();
        if ($itIsControlledJump) {
            $meters -= 2;
        }
        $powerOfWound = $meters + SumAndRound::half($bodyWeight->getValue()) - 5 + $roll1D6->getValue();
        if ($weightOfItemsOverwhelmedBy && $weightOfItemsOverwhelmedBy->getBonus()->getValue() > 0 /* ignore negative values */) {
            $powerOfWound += $weightOfItemsOverwhelmedBy->getBonus()->getValue();
        }
        $armorProtection = $bodyArmorProtection;
        if ($hitToHead) {
            /** same as @link https://pph.drdplus.info/#zraneni */
            $powerOfWound += 2;
            $armorProtection = $helmProtection;
        }
        $powerOfWound += $tables->getLandingSurfacesTable()->getBaseOfWoundsModifier(
            $landingSurfaceCode,
            $agility,
            $armorProtection
        )->getValue();
        $convertedPowerOfWounds = (new WoundsBonus(SumAndRound::round($powerOfWound), $tables->getWoundsTable()))
            ->getWounds()->getValue();
        $convertedAgilityAndAthletics = (new WoundsBonus(
            $agility->getValue() + $athletics->getAthleticsBonus()->getValue(),
            $tables->getWoundsTable())
        )->getWounds()->getValue();
        $woundsValue = $convertedPowerOfWounds - $convertedAgilityAndAthletics;
        if ($woundsValue < 0) {
            $woundsValue = 0;
        }

        return new Wounds($woundsValue, $tables->getWoundsTable());
    }
}