<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\History;

use DrdPlus\Codes\History\AncestryCode;
use DrdPlus\Codes\History\ExceptionalityCode;
use DrdPlus\Tables\History\AncestryTable;
use DrdPlus\Tables\History\BackgroundPointsDistributionTable;
use DrdPlus\Tests\Tables\TableTest;

class BackgroundPointsDistributionTableTest extends TableTest
{

    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame(
            [['background', 'max_points', 'more_than_for_ancestry_up_to']],
            (new BackgroundPointsDistributionTable())->getHeader()
        );
    }

    /**
     * @test
     * @dataProvider provideBackgroundAndAncestryAndExpectedMaxPoints
     * @param int $backgroundValue
     * @param int $maxPointsIfNotLimited
     * @param int $maxPointsFromAncestryToNotBeLimited
     * @param bool $canBeLimited
     */
    public function I_can_get_max_points_to_distribute_by_background(
        $backgroundValue,
        $maxPointsIfNotLimited,
        $maxPointsFromAncestryToNotBeLimited,
        $canBeLimited
    )
    {
        $ancestryCode = $this->createAncestryCode();
        self::assertSame(
            $maxPointsIfNotLimited,
            (new BackgroundPointsDistributionTable())->getMaxPointsToDistribute(
                ExceptionalityCode::getIt($backgroundValue),
                $this->createAncestryTable($ancestryCode, $maxPointsFromAncestryToNotBeLimited),
                $ancestryCode
            )
        );
        for ($pointsForAncestry = $maxPointsFromAncestryToNotBeLimited; $pointsForAncestry > 0; $pointsForAncestry--) {
            self::assertSame(
                $canBeLimited
                    ? ($pointsForAncestry + 3)
                    : $maxPointsIfNotLimited,
                (new BackgroundPointsDistributionTable())->getMaxPointsToDistribute(
                    ExceptionalityCode::getIt($backgroundValue),
                    $this->createAncestryTable($ancestryCode, $pointsForAncestry),
                    $ancestryCode
                )
            );
        }
    }

    public function provideBackgroundAndAncestryAndExpectedMaxPoints()
    {
        return [
            [ExceptionalityCode::ANCESTRY, 8, 8, false],
            [ExceptionalityCode::POSSESSION, 8, 5, true],
            [ExceptionalityCode::SKILLS, 8, 5, true],
        ];
    }

    /**
     * @param AncestryCode $ancestryCode
     * @param int $points
     * @return \Mockery\MockInterface|AncestryTable
     */
    private function createAncestryTable(AncestryCode $ancestryCode, $points)
    {
        $ancestryTable = $this->mockery(AncestryTable::class);
        $ancestryTable->shouldReceive('getBackgroundPointsByAncestryCode')
            ->with($ancestryCode)
            ->andReturn($points);

        return $ancestryTable;
    }

    /**
     * @return \Mockery\MockInterface|AncestryCode
     */
    private function createAncestryCode()
    {
        return $this->mockery(AncestryCode::class);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\History\Exceptions\UnknownExceptionalityCode
     * @expectedExceptionMessageRegExp ~happy~
     */
    public function I_can_not_get_max_points_for_unknown_background()
    {
        (new BackgroundPointsDistributionTable())->getMaxPointsToDistribute(
            $this->createExceptionalityCode('happy'),
            new AncestryTable(),
            AncestryCode::getIt(AncestryCode::FOUNDLING)
        );
    }

    /**
     * @param string $value
     * @return \Mockery\MockInterface|ExceptionalityCode
     */
    private function createExceptionalityCode($value)
    {
        $exceptionalityCode = $this->mockery(ExceptionalityCode::class);
        $exceptionalityCode->shouldReceive('getValue')
            ->andReturn($value);
        $exceptionalityCode->shouldReceive('__toString')
            ->andReturn((string)$value);

        return $exceptionalityCode;
    }
}