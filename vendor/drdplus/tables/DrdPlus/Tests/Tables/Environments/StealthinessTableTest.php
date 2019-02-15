<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Environments;

use DrdPlus\Codes\Environment\ItemStealthinessCode;
use DrdPlus\Tables\Environments\StealthinessTable;
use DrdPlus\Tests\Tables\TableTest;

class StealthinessTableTest extends TableTest
{
    /**
     * @test
     * @dataProvider provideSituationAndExpectedStealthiness
     * @param string $itemStealthinessName
     * @param int $expectedStealthiness
     */
    public function I_can_get_stealthiness_according_to_situation($itemStealthinessName, $expectedStealthiness)
    {
        self::assertSame(
            $expectedStealthiness,
            (new StealthinessTable())->getStealthinessOnSituation(ItemStealthinessCode::getIt($itemStealthinessName))
        );
    }

    public function provideSituationAndExpectedStealthiness()
    {
        $values = [];
        $stealthiness = 0;
        foreach (ItemStealthinessCode::getPossibleValues() as $itemStealthiness) {
            $values[] = [$itemStealthiness, $stealthiness];
            $stealthiness += 3;
        }

        return $values;
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Environments\Exceptions\UnknownStealthinessCode
     * @expectedExceptionMessageRegExp ~in showcase~
     */
    public function I_can_not_get_stealthiness_on_unknown_situation()
    {
        (new StealthinessTable())->getStealthinessOnSituation($this->createItemStealthinessCode('in showcase'));
    }

    /**
     * @param string $value
     * @return \Mockery\MockInterface|ItemStealthinessCode
     */
    private function createItemStealthinessCode($value)
    {
        $itemStealthinessCode = $this->mockery(ItemStealthinessCode::class);
        $itemStealthinessCode->shouldReceive('getValue')
            ->andReturn($value);
        $itemStealthinessCode->shouldReceive('__toString')
            ->andReturn($value);

        return $itemStealthinessCode;
    }
}