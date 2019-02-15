<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Activities;

use DrdPlus\Codes\Environment\LandingSurfaceCode;
use DrdPlus\Codes\JumpTypeCode;
use DrdPlus\BaseProperties\Agility;
use DrdPlus\Tables\Activities\JumpsAndFallsTable;
use DrdPlus\Tables\Environments\LandingSurfacesTable;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Weight\Weight;
use DrdPlus\Tables\Measurements\Weight\WeightBonus;
use DrdPlus\Tables\Measurements\Wounds\Wounds;
use DrdPlus\Tables\Measurements\Wounds\WoundsBonus;
use DrdPlus\Tables\Measurements\Wounds\WoundsTable;
use DrdPlus\Tables\Properties\AthleticsInterface;
use DrdPlus\Tables\Properties\BodyWeightInterface;
use DrdPlus\Tables\Properties\SpeedInterface;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\TableTest;
use Granam\DiceRolls\Templates\Rolls\Roll1d6;
use Granam\Integer\IntegerWithHistory;
use Granam\Integer\PositiveInteger;
use Granam\Integer\PositiveIntegerObject;

class JumpsAndFallsTableTest extends TableTest
{
    /**
     * @test
     * @dataProvider provideValuesForModifierToJump
     * @param string $jumpType
     * @param float $ranDistance
     * @param int $expectedModifierToJump
     */
    public function I_can_get_modifier_to_jump($jumpType, $ranDistance, $expectedModifierToJump): void
    {
        self::assertSame(
            $expectedModifierToJump,
            (new JumpsAndFallsTable())
                ->getModifierToJump(JumpTypeCode::getIt($jumpType), $this->createDistance($ranDistance))
        );
    }

    public function provideValuesForModifierToJump(): array
    {
        return [
            [JumpTypeCode::HIGH_JUMP, 4.9, -6],
            [JumpTypeCode::HIGH_JUMP, 5, 3],
            [JumpTypeCode::BROAD_JUMP, 0, -3],
            [JumpTypeCode::BROAD_JUMP, 5.1, 6],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_length_of_jump(): void
    {
        self::assertSame(
            62 /* 123 / 2 */ - 3 + 456 + 789,
            (new JumpsAndFallsTable())
                ->getJumpLength(
                    $this->createSpeed(123),
                    $this->createAthletics(456),
                    JumpTypeCode::getIt(JumpTypeCode::BROAD_JUMP),
                    $this->createDistance(0),
                    $this->createRoll1d6(789)
                )
        );
    }

    /**
     * @param int $value
     * @return \Mockery\MockInterface|SpeedInterface
     */
    private function createSpeed($value)
    {
        $speed = $this->mockery(SpeedInterface::class);
        $speed->shouldReceive('getValue')
            ->andReturn($value);

        return $speed;
    }

    /**
     * @param int $value
     * @return \Mockery\MockInterface|AthleticsInterface
     */
    private function createAthletics($value)
    {
        $athletics = $this->mockery(AthleticsInterface::class);
        $athletics->shouldReceive('getAthleticsBonus')
            ->andReturn($athleticsBonus = $this->mockery(PositiveInteger::class));
        $athleticsBonus->shouldReceive('getValue')
            ->andReturn($value);

        return $athletics;
    }

    /**
     * @param int $value
     * @return \Mockery\MockInterface|Roll1d6
     */
    private function createRoll1d6($value): Roll1d6
    {
        $roll1d6 = $this->mockery(Roll1d6::class);
        $roll1d6->shouldReceive('getValue')
            ->andReturn($value);

        return $roll1d6;
    }

    /**
     * @test
     * @dataProvider provideValuesForWoundsFromJumpOrFall
     * @param float $distanceInMeters
     * @param int $bodyWeight
     * @param int $itemsWeightBonus
     * @param int $roll1d6
     * @param bool $itIsControlledJump
     * @param int $bodyArmorProtectionValue
     * @param bool $hitToHead
     * @param int $helmProtectionValue
     * @param int $modifierFromLandingSurface
     * @param int $expectedPowerOfWound
     * @param int $powerOfWoundAsWounds
     * @param int $agilityValue
     * @param int $athleticsValue
     * @param int $agilityAsWounds
     * @param int $expectedWounds
     */
    public function I_can_get_wounds_from_jump_or_fall(
        float $distanceInMeters,
        int $bodyWeight,
        ?int $itemsWeightBonus,
        int $roll1d6,
        bool $itIsControlledJump,
        int $bodyArmorProtectionValue,
        bool $hitToHead,
        int $helmProtectionValue,
        int $modifierFromLandingSurface,
        int $expectedPowerOfWound,
        int $powerOfWoundAsWounds,
        int $agilityValue,
        int $athleticsValue,
        int $agilityAsWounds,
        int $expectedWounds
    ): void
    {
        $wounds = (new JumpsAndFallsTable())
            ->getWoundsFromJumpOrFall(
                $this->createDistance($distanceInMeters),
                $this->createBodyWeight($bodyWeight),
                $itemsWeightBonus !== null ? $this->createWeight($itemsWeightBonus) : null,
                $this->createRoll1d6($roll1d6),
                $itIsControlledJump,
                $agility = $this->createAgility($agilityValue),
                $this->createAthletics($athleticsValue),
                $landingSurfaceCode = $this->createLandingSurfaceCode('foo'),
                $bodyArmorProtection = new PositiveIntegerObject($bodyArmorProtectionValue),
                $hitToHead,
                $helmProtection = new PositiveIntegerObject($helmProtectionValue),
                $this->createTables(
                    $this->createWoundsTable($expectedPowerOfWound, $powerOfWoundAsWounds, $agilityValue + $athleticsValue, $agilityAsWounds),
                    $this->createLandingSurfacesTable(
                        $landingSurfaceCode,
                        $agility,
                        $hitToHead ? $helmProtection : $bodyArmorProtection,
                        $modifierFromLandingSurface
                    )
                )
            );
        self::assertSame($expectedWounds, $wounds->getValue());
    }

    /**
     * @param WoundsTable $woundsTable
     * @param LandingSurfacesTable $landingSurfacesTable
     * @return Tables|\Mockery\MockInterface
     */
    private function createTables(WoundsTable $woundsTable, LandingSurfacesTable $landingSurfacesTable): Tables
    {
        $tables = $this->mockery(Tables::class);
        $tables->shouldReceive('getWoundsTable')
            ->andReturn($woundsTable);
        $tables->shouldReceive('getLandingSurfacesTable')
            ->andReturn($landingSurfacesTable);

        return $tables;
    }

    public function provideValuesForWoundsFromJumpOrFall(): array
    {
        // distance, weight, itemsWeightBonus, roll, isControlled, bodyArmor, hitToHead, helm protection, surface modifier,
        // expected power of wound, powerOfWoundAsWounds, agility, athletics, agilityAsWounds, expected wounds
        return [
            [111.1, 222, 0, 333, false, 444, false, 999, -550, 0, 123, 555, 666, 124 /* higher bonus from agility than wounds */, 0],
            [111.1, 222, 987, 333, false, 444, false, 999, -550, 987 /* items weight */, 123, 555, 666, 124 /* higher bonus from agility than wounds */, 0],
            [111.2, 222, null, 333, false, 444, false, 999, -550, 0, 124, 555, 666, 123 /* higher wounds than bonus from agility */, 1],
            [111.2, 222, -5 /* ignored */, 333, false, 444, true /* hit to head */, 444 /* helm */, -550, 2 /* base of wounds */, 124, 555, 666, 123 /* higher wounds than bonus from agility */, 1],
            [111.3, 222, 0, 333, true /* controlled jump */, 444, false, 0, -548, 0, 123, 555, 666, 124, 0],
            [111.4, 222, null, 333, false /* fall */, 444, false, 0, -548, 2, 123, 555, 666, 124, 0],
            [111.5, 222, -999 /* ignored */, 333, false, 444, false, 0, -500, 51 /* +1 because of rounded distance */, 123, 555, 666, 50, 73],
            [-0.5, 80, 0, 1, false, 0, false, 0, -35, 1 /* +1 because int - 0.5 = 0.5 is rounded to 1 */, 123, 555, 666, 50, 73],
            [-0.6, 80, 0, 1, false, 0, false, 0, -35, 0 /* because int - 0.6 = 0.4 is rounded to 0 */, 123, 555, 666, 50, 73],
        ];
    }

    /**
     * @param float $meters
     * @return \Mockery\MockInterface|Distance
     */
    private function createDistance($meters): Distance
    {
        $distance = $this->mockery(Distance::class);
        $distance->shouldReceive('getMeters')
            ->andReturn($meters);

        return $distance;
    }

    /**
     * @param int $value
     * @return \Mockery\MockInterface|BodyWeightInterface
     */
    private function createBodyWeight($value): BodyWeightInterface
    {
        $weight = $this->mockery(BodyWeightInterface::class);
        $weight->shouldReceive('getValue')
            ->andReturn($value);

        return $weight;
    }

    /**
     * @param int $itemsWeightBonus
     * @return \Mockery\MockInterface|Weight
     */
    private function createWeight(int $itemsWeightBonus): Weight
    {
        $weight = $this->mockery(Weight::class);
        $weight->shouldReceive('getBonus')
            ->andReturn($weightBonus = $this->mockery(WeightBonus::class));
        $weightBonus->shouldReceive('getValue')
            ->andReturn($itemsWeightBonus);

        return $weight;
    }

    /**
     * @param int $value
     * @return \Mockery\MockInterface|Agility
     */
    private function createAgility($value)
    {
        $agility = $this->mockery(Agility::class);
        $agility->shouldReceive('getValue')
            ->andReturn($value);

        return $agility;
    }

    /**
     * @param string $value
     * @return \Mockery\MockInterface|LandingSurfaceCode
     */
    private function createLandingSurfaceCode($value)
    {
        $landingSurfaceCode = $this->mockery(LandingSurfaceCode::class);
        $landingSurfaceCode->shouldReceive('getValue')
            ->andReturn($value);
        $landingSurfaceCode->shouldReceive('__toString')
            ->andReturn((string)$value);

        return $landingSurfaceCode;
    }

    /**
     * @param int $expectedPowerOfWound
     * @param int $powerOfWoundAsWounds
     * @param int $expectedAgilityAndAthleticsValue
     * @param int $agilityAsWounds
     * @return \Mockery\MockInterface|WoundsTable
     */
    private function createWoundsTable($expectedPowerOfWound, $powerOfWoundAsWounds, $expectedAgilityAndAthleticsValue, $agilityAsWounds)
    {
        $woundsTable = $this->mockery(WoundsTable::class);
        $woundsTable->shouldReceive('toWounds')
            ->zeroOrMoreTimes()
            ->with($this->type(WoundsBonus::class))
            ->andReturnUsing(function (WoundsBonus $woundsBonus)
            use ($expectedPowerOfWound, $powerOfWoundAsWounds, $expectedAgilityAndAthleticsValue, $agilityAsWounds) {
                $wounds = $this->mockery(Wounds::class);
                if ($woundsBonus->getValue() === $expectedPowerOfWound) {
                    $wounds->shouldReceive('getValue')
                        ->andReturn($powerOfWoundAsWounds);
                } else if ($woundsBonus->getValue() === $expectedAgilityAndAthleticsValue) {
                    $wounds->shouldReceive('getValue')
                        ->andReturn($agilityAsWounds);
                } else {
                    self::fail(
                        'Unexpected value of wounds bonus ' . var_export($woundsBonus->getValue(), true)
                        . ', expected one of ' . $expectedPowerOfWound . ' or ' . $expectedAgilityAndAthleticsValue
                    );
                }

                return $wounds;
            });

        return $woundsTable;
    }

    /**
     * @param LandingSurfaceCode $landingSurfaceCode
     * @param Agility $agility
     * @param PositiveInteger $armorProtection
     * @param int $modifier
     * @return \Mockery\MockInterface|LandingSurfacesTable
     */
    private function createLandingSurfacesTable(
        LandingSurfaceCode $landingSurfaceCode,
        Agility $agility,
        PositiveInteger $armorProtection,
        int $modifier
    )
    {
        $landingSurfacesTable = $this->mockery(LandingSurfacesTable::class);
        $landingSurfacesTable->shouldReceive('getBaseOfWoundsModifier')
            ->zeroOrMoreTimes()
            ->with($landingSurfaceCode, $agility, $armorProtection)
            ->andReturn(new IntegerWithHistory($modifier));

        return $landingSurfacesTable;
    }
}