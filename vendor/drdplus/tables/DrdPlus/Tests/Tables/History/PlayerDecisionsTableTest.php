<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\History;

use DrdPlus\Codes\History\FateCode;
use DrdPlus\Tables\History\PlayerDecisionsTable;
use DrdPlus\Tests\Tables\TableTest;

class PlayerDecisionsTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame(
            [['fate', 'points_to_primary_properties', 'points_to_secondary_properties', 'maximum_to_single_property']],
            (new PlayerDecisionsTable())->getHeader()
        );
    }

    /**
     * @test
     * @dataProvider provideDecisionAndExpectedPointsToPrimaryProperties
     * @param string $decisionValue
     * @param int $expectedPoints
     */
    public function I_can_get_points_to_primary_properties($decisionValue, $expectedPoints)
    {
        self::assertSame(
            $expectedPoints,
            (new PlayerDecisionsTable())->getPointsToPrimaryProperties(FateCode::getIt($decisionValue))
        );
    }

    public function provideDecisionAndExpectedPointsToPrimaryProperties()
    {
        return [
            [FateCode::EXCEPTIONAL_PROPERTIES, 3],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 2],
            [FateCode::GOOD_BACKGROUND, 1],
        ];
    }

    /**
     * @test
     * @dataProvider provideDecisionAndExpectedPointsToSecondaryProperties
     * @param string $decisionValue
     * @param int $expectedPoints
     */
    public function I_can_get_points_to_secondary_properties($decisionValue, $expectedPoints)
    {
        self::assertSame(
            $expectedPoints,
            (new PlayerDecisionsTable())->getPointsToSecondaryProperties(FateCode::getIt($decisionValue))
        );
    }

    public function provideDecisionAndExpectedPointsToSecondaryProperties()
    {
        return [
            [FateCode::EXCEPTIONAL_PROPERTIES, 6],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 4],
            [FateCode::GOOD_BACKGROUND, 2],
        ];
    }

    /**
     * @test
     * @dataProvider provideDecisionAndExpectedMaximumPointsToSingleProperty
     * @param string $decisionValue
     * @param int $expectedPoints
     */
    public function I_can_get_maximum_points_to_single_property($decisionValue, $expectedPoints)
    {
        self::assertSame(
            $expectedPoints,
            (new PlayerDecisionsTable())->getMaximumToSingleProperty(FateCode::getIt($decisionValue))
        );
    }

    public function provideDecisionAndExpectedMaximumPointsToSingleProperty()
    {
        return [
            [FateCode::EXCEPTIONAL_PROPERTIES, 3],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 2],
            [FateCode::GOOD_BACKGROUND, 1],
        ];
    }
}