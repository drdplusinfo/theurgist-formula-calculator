<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Body;

use DrdPlus\Calculations\SumAndRound;
use DrdPlus\Properties\Body\Height;
use DrdPlus\Tables\Body\CorrectionByHeightTable;
use DrdPlus\Tables\Properties\HeightInterface;
use DrdPlus\Tests\Tables\TableTest;

class CorrectionByHeightTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame([['height', 'correction']], (new CorrectionByHeightTable())->getHeader());
    }

    /**
     * @test
     * @dataProvider provideHeightAndExpectedCorrection
     * @param int $height
     * @param int $expectedCorrection
     */
    public function I_can_get_correction_by_height($height, $expectedCorrection)
    {
        $correction = (new CorrectionByHeightTable())->getCorrectionByHeight($this->createHeight($height));
        self::assertSame($expectedCorrection, $correction);
        self::assertSame(SumAndRound::ceiledThird($height) - 2, $correction);
    }

    public function provideHeightAndExpectedCorrection()
    {
        return [
            [1, -1],
            [2, -1],
            [3, -1],
            [4, 0],
            [5, 0],
            [6, 0],
            [7, 1],
            [8, 1],
            [9, 1],
        ];
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|HeightInterface
     */
    private function createHeight($value)
    {
        $height = $this->mockery(HeightInterface::class);
        $height->shouldReceive('getValue')
            ->andReturn($value);
        $height->shouldReceive('__toString')
            ->andReturn((string)$value);

        return $height;
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Body\Exceptions\UnexpectedHeightToGetCorrectionFor
     * @expectedExceptionMessageRegExp ~0~
     */
    public function I_can_not_get_correction_for_too_low_height()
    {
        (new CorrectionByHeightTable())->getCorrectionByHeight($this->createHeight(0));
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Body\Exceptions\UnexpectedHeightToGetCorrectionFor
     * @expectedExceptionMessageRegExp ~10~
     */
    public function I_can_not_get_correction_for_too_high_height()
    {
        (new CorrectionByHeightTable())->getCorrectionByHeight($this->createHeight(10));
    }
}