<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Combat\Attacks;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Tables\Combat\Attacks\AttackNumberByContinuousDistanceTable;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tests\Tables\Combat\Attacks\Partials\AbstractAttackNumberByDistanceTableTest;
use DrdPlus\Calculations\SumAndRound;

class AttackNumberByContinuousDistanceTableTest extends AbstractAttackNumberByDistanceTableTest
{
    /**
     * @test
     */
    public function I_can_get_header(): void
    {
        self::assertSame(
            [
                ['distance_in_meters', 'distance_bonus', 'ranged_attack_number_modifier'],
            ],
            (new AttackNumberByContinuousDistanceTable())->getHeader()
        );
    }

    public function provideDistanceAndExpectedModifier(): array
    {
        $testValues = [];
        $distanceTable = new DistanceTable();
        for ($distanceBonus = 1; $distanceBonus < 100; $distanceBonus++) {
            $distanceInMeters = (new DistanceBonus($distanceBonus, $distanceTable))->getDistance()->getMeters();
            $attackNumberModifier = -(SumAndRound::half($distanceBonus) - 9); // PPH page 104 left column
            $testValues[] = [$distanceInMeters, $attackNumberModifier];
        }

        return $testValues;
    }

    /**
     * @test
     */
    public function I_can_not_get_attack_number_modifier_with_enormous_distance(): void
    {
        $this->expectException(\DrdPlus\Tables\Combat\Attacks\Exceptions\DistanceOutOfKnownValues::class);
        $this->expectExceptionMessageRegExp('~999999 m~');
        (new AttackNumberByContinuousDistanceTable())
            ->getAttackNumberModifierByDistance(new Distance(999999, DistanceUnitCode::METER, new DistanceTable()));
    }

    /**
     * @test
     */
    public function I_can_use_constant_with_distance_not_affecting_attack_number(): void
    {
        self::assertSame(
            0,
            (new AttackNumberByContinuousDistanceTable())->getAttackNumberModifierByDistance(
                new Distance(
                    AttackNumberByContinuousDistanceTable::DISTANCE_WITH_NO_IMPACT_TO_ATTACK_NUMBER,
                    Distance::METER,
                    new DistanceTable()
                )
            )
        );
    }

}