<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Combat\Attacks;

use DrdPlus\Tables\Combat\Attacks\AttackNumberByDistanceTable;
use DrdPlus\Tests\Tables\Combat\Attacks\Partials\AbstractAttackNumberByDistanceTableTest;

class AttackNumberByDistanceTableTest extends AbstractAttackNumberByDistanceTableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame(
            [
                ['distance_in_meters_from', 'distance_bonus', 'ranged_attack_number_modifier'],
            ],
            (new AttackNumberByDistanceTable())->getHeader()
        );
    }

    public function provideDistanceAndExpectedModifier(): array
    {
        return [
            [0, 3],
            [1, 3],
            [2, 3],
            [3.1, 3],
            [3.2, 3],
            [4, 3],
            [5.5, 3],
            [5.6, 0],
            [5.7, 0],
            [10.9, 0],
            [11, -3],
            [21.9, -3],
            [22, -6],
            [44.9, -6],
            [45, -9],
            [90, -12],
            [350, -12],
        ];
    }

}