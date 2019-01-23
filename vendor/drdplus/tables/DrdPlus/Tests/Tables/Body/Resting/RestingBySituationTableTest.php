<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Body\Resting;

use DrdPlus\Codes\Body\RestConditionsCode;
use DrdPlus\Tables\Body\Resting\RestingBySituationTable;
use DrdPlus\Tables\Body\Resting\RestingSituationPercents;
use DrdPlus\Tests\Tables\TableTest;

class RestingBySituationTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        $restingBySituationTable = new RestingBySituationTable();
        self::assertSame(
            [['situation', 'bonus_from', 'bonus_to', 'can_be_more']],
            $restingBySituationTable->getHeader()
        );
    }

    /**
     * @test
     */
    public function I_can_get_all_values()
    {
        $restingBySituationTable = new RestingBySituationTable();
        self::assertSame(
            [
                RestConditionsCode::HALF_TIME_OF_REST_OR_SLEEP => ['bonus_from' => -6, 'bonus_to' => -6, 'can_be_more' => false],
                RestConditionsCode::QUARTER_TIME_OF_REST_OR_SLEEP => ['bonus_from' => -12, 'bonus_to' => -12, 'can_be_more' => false],
                RestConditionsCode::FOUL_CONDITIONS => ['bonus_from' => -12, 'bonus_to' => -6, 'can_be_more' => true],
                RestConditionsCode::BAD_CONDITIONS => ['bonus_from' => -5, 'bonus_to' => -3, 'can_be_more' => false],
                RestConditionsCode::IMPAIRED_CONDITIONS => ['bonus_from' => -2, 'bonus_to' => -1, 'can_be_more' => false],
                RestConditionsCode::GOOD_CONDITIONS => ['bonus_from' => 0, 'bonus_to' => 0, 'can_be_more' => false],
            ],
            $restingBySituationTable->getIndexedValues()
        );
    }

    /**
     * @test
     * @dataProvider provideSituationAndExpectedBonus
     * @param string $situationCode
     * @param int $percentsOfSituation
     * @param int $expectedRestingBonus
     */
    public function I_can_get_resting_bonus_by_situation($situationCode, $percentsOfSituation, $expectedRestingBonus)
    {
        $restingBySituationTable = new RestingBySituationTable();
        self::assertSame(
            $expectedRestingBonus,
            $restingBySituationTable->getRestingMalusBySituation(
                $situationCode,
                $this->createRestingSituationPercents($percentsOfSituation)
            )
        );
    }

    public function provideSituationAndExpectedBonus()
    {
        return [
            [RestConditionsCode::HALF_TIME_OF_REST_OR_SLEEP, 100, -6],
            [RestConditionsCode::HALF_TIME_OF_REST_OR_SLEEP, 0, -6],
            [RestConditionsCode::QUARTER_TIME_OF_REST_OR_SLEEP, 50, -12],
            [RestConditionsCode::FOUL_CONDITIONS, 0, -6],
            [RestConditionsCode::FOUL_CONDITIONS, 100, -12],
            [RestConditionsCode::FOUL_CONDITIONS, 150, -15],
            [RestConditionsCode::BAD_CONDITIONS, 0, -3],
            [RestConditionsCode::BAD_CONDITIONS, 50, -4],
            [RestConditionsCode::BAD_CONDITIONS, 80, -5],
            [RestConditionsCode::IMPAIRED_CONDITIONS, 0, -1],
            [RestConditionsCode::IMPAIRED_CONDITIONS, 50, -2],
            [RestConditionsCode::GOOD_CONDITIONS, 12, 0],
        ];
    }

    /**
     * @param int $percents
     * @return \Mockery\MockInterface|RestingSituationPercents
     */
    private function createRestingSituationPercents($percents)
    {
        $healingConditionsPercents = $this->mockery(RestingSituationPercents::class);
        $healingConditionsPercents->shouldReceive('getValue')
            ->andReturn($percents);
        $healingConditionsPercents->shouldReceive('getRate')
            ->andReturn($percents / 100);

        return $healingConditionsPercents;
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Body\Resting\Exceptions\UnexpectedRestingSituationPercents
     * @expectedExceptionMessageRegExp ~101~
     */
    public function I_can_not_get_higher_bonus_than_hundred_percents_if_conditions_do_not_allow_it()
    {
        (new RestingBySituationTable())->getRestingMalusBySituation(
            RestConditionsCode::IMPAIRED_CONDITIONS,
            new RestingSituationPercents(101)
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Body\Resting\Exceptions\UnknownCodeOfRestingInfluence
     * @expectedExceptionMessageRegExp ~arrested~
     */
    public function I_can_not_get_bonus_for_unknown_situation()
    {
        (new RestingBySituationTable())->getRestingMalusBySituation('arrested', $this->createRestingSituationPercents(0));
    }
}
