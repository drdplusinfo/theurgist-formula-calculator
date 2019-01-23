<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Activities;

use DrdPlus\Codes\FoodTypeCode;
use DrdPlus\Tables\Activities\CatchQualitiesTable;
use DrdPlus\Tests\Tables\TableTest;
use Granam\Integer\IntegerObject;

class CatchQualitiesTableTest extends TableTest
{
    /**
     * @test
     * @param int $catchQualityValue
     * @param int $expectedHealingAndRest
     * @dataProvider provideCatchQualityAndExpectedHealingAndRest
     */
    public function I_can_get_healing_and_rest_by_catch_quality($catchQualityValue, $expectedHealingAndRest)
    {
        self::assertSame(
            $expectedHealingAndRest,
            (new CatchQualitiesTable())->getHealingAndRestByCatchQuality(new IntegerObject($catchQualityValue))
        );
    }

    public function provideCatchQualityAndExpectedHealingAndRest()
    {
        return [
            [-10, -7],
            [10, -3],
            [11, -2],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_possible_food_types_by_catch_quality()
    {
        $catchQualitiesTable = new CatchQualitiesTable();
        self::assertSame(
            [],
            $catchQualitiesTable->getPossibleFoodTypesByCatchQuality(new IntegerObject(0))
        );
        self::assertSame(
            [
                FoodTypeCode::getIt(FoodTypeCode::CROP_COLLECTION),
                FoodTypeCode::getIt(FoodTypeCode::INSECTS_OR_WORMS),
            ],
            $catchQualitiesTable->getPossibleFoodTypesByCatchQuality(new IntegerObject(10))
        );
        self::assertSame(
            array_map(function ($foodTypeValue) {
                return FoodTypeCode::getIt($foodTypeValue);
            }, FoodTypeCode::getPossibleValues()),
            $catchQualitiesTable->getPossibleFoodTypesByCatchQuality(new IntegerObject(20))
        );
    }
}