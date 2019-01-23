<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Combat\Attacks\Partials;

use DrdPlus\Tables\Combat\Attacks\Partials\AbstractAttackNumberByDistanceTable;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tests\Tables\TableTest;

abstract class AbstractAttackNumberByDistanceTableTest extends TableTest
{
    /**
     * @test
     * @param float $distanceInMeters
     * @param int $expectedAttackNumberModifier
     * @dataProvider provideDistanceAndExpectedModifier
     */
    public function I_can_get_attack_number_modifier_by_distance($distanceInMeters, $expectedAttackNumberModifier)
    {
        $sutClass = self::getSutClass();
        /** @var AbstractAttackNumberByDistanceTable $sut */
        $sut = new $sutClass();
        self::assertSame(
            $expectedAttackNumberModifier,
            $sut->getAttackNumberModifierByDistance($this->createDistance($distanceInMeters))
        );
    }

    /**
     * @return array|mixed[][]
     */
    abstract public function provideDistanceAndExpectedModifier(): array;

    /**
     * @param int $distanceInMeters
     * @return \Mockery\MockInterface|Distance
     */
    protected function createDistance($distanceInMeters)
    {
        $distance = $this->mockery(Distance::class);
        $distance->shouldReceive('getMeters')
            ->andReturn($distanceInMeters);

        return $distance;
    }
}