<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\History;

use DrdPlus\Codes\History\FateCode;
use DrdPlus\Tables\History\BackgroundPointsTable;
use DrdPlus\Tests\Tables\TableTest;

class BackgroundPointsTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame([['fate', 'background_points']], (new BackgroundPointsTable())->getHeader());
    }

    /**
     * @test
     * @dataProvider providePlayerDecisionAndExpectedBackgroundPoints
     * @param string $playerDecision
     * @param int $expectedBackgroundPoints
     */
    public function I_can_get_background_points_for_fate($playerDecision, $expectedBackgroundPoints)
    {
        $backgroundPointsTable = new BackgroundPointsTable();
        self::assertSame(
            $expectedBackgroundPoints,
            $backgroundPointsTable->getBackgroundPointsByPlayerDecision(FateCode::getIt($playerDecision))
        );
    }

    public function providePlayerDecisionAndExpectedBackgroundPoints()
    {
        return [
            [FateCode::EXCEPTIONAL_PROPERTIES, 5],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 10],
            [FateCode::GOOD_BACKGROUND, 15],
        ];
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\History\Exceptions\UnknownFate
     * @expectedExceptionMessageRegExp ~homeless~
     */
    public function I_can_not_get_background_points_for_unknown_fate()
    {
        (new BackgroundPointsTable())->getBackgroundPointsByPlayerDecision($this->createPlayerDecision('homeless'));
    }

    /**
     * @param string $value
     * @return \Mockery\MockInterface|FateCode
     */
    private function createPlayerDecision($value)
    {
        $fate = $this->mockery(FateCode::class);
        $fate->shouldReceive('getValue')
            ->andReturn($value);
        $fate->shouldReceive('__toString')
            ->andReturn((string)$value);

        return $fate;
    }
}