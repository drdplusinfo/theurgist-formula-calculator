<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\History;

use DrdPlus\Codes\History\AncestryCode;
use DrdPlus\Tables\History\AncestryTable;
use DrdPlus\Tests\Tables\TableTest;
use Granam\Integer\PositiveIntegerObject;

class AncestryTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame([['background_points', 'ancestry']], (new AncestryTable())->getHeader());
    }

    /**
     * @test
     * @dataProvider provideBackgroundPointsAndExpectedAncestry
     * @param int $backgroundPoints
     * @param string $expectedAncestryValue
     */
    public function I_can_get_ancestry_by_background_points_and_vice_versa($backgroundPoints, $expectedAncestryValue)
    {
        $ancestryTable = new AncestryTable();
        $expectedAncestryCode = AncestryCode::getIt($expectedAncestryValue);
        self::assertSame(
            $expectedAncestryCode,
            $ancestryTable->getAncestryCodeByBackgroundPoints(new PositiveIntegerObject($backgroundPoints))
        );
        self::assertSame(
            $backgroundPoints,
            $ancestryTable->getBackgroundPointsByAncestryCode($expectedAncestryCode)
        );
    }

    public function provideBackgroundPointsAndExpectedAncestry()
    {
        return [
            [0, AncestryCode::FOUNDLING],
            [1, AncestryCode::ORPHAN],
            [2, AncestryCode::FROM_INCOMPLETE_FAMILY],
            [3, AncestryCode::FROM_DOUBTFULLY_FAMILY],
            [4, AncestryCode::FROM_MODEST_FAMILY],
            [5, AncestryCode::FROM_WEALTHY_FAMILY],
            [6, AncestryCode::FROM_WEALTHY_AND_INFLUENTIAL_FAMILY],
            [7, AncestryCode::NOBLE],
            [8, AncestryCode::NOBLE_FROM_POWERFUL_FAMILY],
        ];
    }

    /**
     * @test
     */
    public function I_can_not_get_ancestry_by_invalid_background_points()
    {
        $this->expectException(\DrdPlus\Tables\History\Exceptions\UnexpectedBackgroundPoints::class);
        $this->expectExceptionMessageRegExp('~9~');
        (new AncestryTable())->getAncestryCodeByBackgroundPoints(new PositiveIntegerObject(9));
    }

    /**
     * @test
     */
    public function I_can_not_get_background_points_by_unknown_ancestry()
    {
        $this->expectException(\DrdPlus\Tables\History\Exceptions\UnknownAncestryCode::class);
        $this->expectExceptionMessageRegExp('~king kong~');
        (new AncestryTable())->getBackgroundPointsByAncestryCode($this->createAncestryCode('king kong'));
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|AncestryCode
     */
    private function createAncestryCode($value)
    {
        $ancestryCode = $this->mockery(AncestryCode::class);
        $ancestryCode->shouldReceive('getValue')
            ->andReturn($value);
        $ancestryCode->shouldReceive('__toString')
            ->andReturn((string)$value);

        return $ancestryCode;
    }
}