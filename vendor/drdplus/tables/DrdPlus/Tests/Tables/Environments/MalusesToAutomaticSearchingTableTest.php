<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Environments;

use DrdPlus\Codes\ActivityIntensityCode;
use DrdPlus\Tables\Environments\MalusesToAutomaticSearchingTable;
use DrdPlus\Tests\Tables\TableTest;

class MalusesToAutomaticSearchingTableTest extends TableTest
{
    /**
     * @test
     * @dataProvider provideActivityIntensityAndExpectedMalusToSearching
     * @param string $activityIntensity
     * @param int $expectedMalus
     */
    public function I_can_get_malus_to_searching($activityIntensity, $expectedMalus)
    {
        self::assertSame(
            $expectedMalus,
            (new MalusesToAutomaticSearchingTable())
                ->getMalusWhenSearchingAtTheSameTimeWith(ActivityIntensityCode::getIt($activityIntensity))
        );
    }

    public function provideActivityIntensityAndExpectedMalusToSearching()
    {
        return [
            [ActivityIntensityCode::AUTOMATIC_ACTIVITY, -3],
            [ActivityIntensityCode::ACTIVITY_WITH_MODERATE_CONCENTRATION, -6],
            [ActivityIntensityCode::ACTIVITY_WITH_FULL_CONCENTRATION, -9],
        ];
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Environments\Exceptions\CanNotSearchWithCurrentActivity
     * @expectedExceptionMessageRegExp ~trans~
     */
    public function I_can_not_get_malus_to_searching_when_in_trans()
    {
        (new MalusesToAutomaticSearchingTable())
            ->getMalusWhenSearchingAtTheSameTimeWith(ActivityIntensityCode::getIt(ActivityIntensityCode::TRANS));
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Environments\Exceptions\CanNotSearchWithCurrentActivity
     * @expectedExceptionMessageRegExp ~energizing~
     */
    public function I_can_not_get_malus_to_searching_when_doing_activity_with_unknown_intensity()
    {
        (new MalusesToAutomaticSearchingTable())
            ->getMalusWhenSearchingAtTheSameTimeWith($this->createActivityIntensityCode('energizing'));
    }

    /**
     * @param string $value
     * @return \Mockery\MockInterface|ActivityIntensityCode
     */
    private function createActivityIntensityCode($value)
    {
        $activityIntensityCode = $this->mockery(ActivityIntensityCode::class);
        $activityIntensityCode->shouldReceive('getValue')
            ->andReturn($value);
        $activityIntensityCode->shouldReceive('__toString')
            ->andReturn((string)$value);

        return $activityIntensityCode;
    }
}