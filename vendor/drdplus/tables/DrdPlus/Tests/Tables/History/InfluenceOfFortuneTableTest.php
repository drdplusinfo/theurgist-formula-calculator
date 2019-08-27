<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\History;

use DrdPlus\Codes\History\FateCode;
use DrdPlus\Tables\History\InfluenceOfFortuneTable;
use DrdPlus\Tests\Tables\TableTest;

class InfluenceOfFortuneTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        $expectedHeader = ['fate'];
        for ($roll = 1; $roll <= 6; $roll++) {
            foreach (['primary_property_on_', 'secondary_property_on_'] as $name) {
                $expectedHeader[] = $name . $roll;
            }
        }

        self::assertSame(
            [$expectedHeader],
            (new InfluenceOfFortuneTable())->getHeader()
        );
    }

    /**
     * @test
     * @dataProvider provideFateAndExpectedPrimaryProperty
     * @param string $fate
     * @param int $diceRoll
     * @param int $expectedPrimaryProperty
     */
    public function I_can_get_primary_property_for_each_fate($fate, $diceRoll, $expectedPrimaryProperty)
    {
        self::assertSame(
            $expectedPrimaryProperty,
            (new InfluenceOfFortuneTable())->getPrimaryPropertyOnFate(FateCode::getIt($fate), $diceRoll)
        );
    }

    public function provideFateAndExpectedPrimaryProperty()
    {
        return [
            [FateCode::EXCEPTIONAL_PROPERTIES, 1, 1],
            [FateCode::EXCEPTIONAL_PROPERTIES, 2, 1],
            [FateCode::EXCEPTIONAL_PROPERTIES, 3, 1],
            [FateCode::EXCEPTIONAL_PROPERTIES, 4, 2],
            [FateCode::EXCEPTIONAL_PROPERTIES, 5, 2],
            [FateCode::EXCEPTIONAL_PROPERTIES, 6, 2],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 1, 0],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 2, 0],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 3, 1],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 4, 1],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 5, 2],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 6, 2],
            [FateCode::GOOD_BACKGROUND, 1, 0],
            [FateCode::GOOD_BACKGROUND, 2, 0],
            [FateCode::GOOD_BACKGROUND, 3, 0],
            [FateCode::GOOD_BACKGROUND, 4, 1],
            [FateCode::GOOD_BACKGROUND, 5, 1],
            [FateCode::GOOD_BACKGROUND, 6, 1],
        ];
    }

    /**
     * @test
     */
    public function I_can_not_get_primary_property_on_non_1d6_roll()
    {
        $this->expectException(\DrdPlus\Tables\History\Exceptions\UnexpectedDiceRoll::class);
        $this->expectExceptionMessageRegExp('~7~');
        (new InfluenceOfFortuneTable())->getPrimaryPropertyOnFate(FateCode::getIt(FateCode::GOOD_BACKGROUND), 7);
    }

    /**
     * @test
     * @dataProvider provideFateAndExpectedSecondaryProperty
     * @param string $fate
     * @param int $diceRoll
     * @param int $expectedSecondaryProperty
     */
    public function I_can_get_secondary_property_for_each_fate($fate, $diceRoll, $expectedSecondaryProperty)
    {
        self::assertSame(
            $expectedSecondaryProperty,
            (new InfluenceOfFortuneTable())->getSecondaryPropertyOnFate(FateCode::getIt($fate), $diceRoll)
        );
    }

    public function provideFateAndExpectedSecondaryProperty()
    {
        return [
            [FateCode::EXCEPTIONAL_PROPERTIES, 1, 0],
            [FateCode::EXCEPTIONAL_PROPERTIES, 2, 1],
            [FateCode::EXCEPTIONAL_PROPERTIES, 3, 1],
            [FateCode::EXCEPTIONAL_PROPERTIES, 4, 2],
            [FateCode::EXCEPTIONAL_PROPERTIES, 5, 2],
            [FateCode::EXCEPTIONAL_PROPERTIES, 6, 3],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 1, 0],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 2, 0],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 3, 1],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 4, 1],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 5, 2],
            [FateCode::COMBINATION_OF_PROPERTIES_AND_BACKGROUND, 6, 2],
            [FateCode::GOOD_BACKGROUND, 1, 0],
            [FateCode::GOOD_BACKGROUND, 2, 0],
            [FateCode::GOOD_BACKGROUND, 3, 0],
            [FateCode::GOOD_BACKGROUND, 4, 1],
            [FateCode::GOOD_BACKGROUND, 5, 1],
            [FateCode::GOOD_BACKGROUND, 6, 1],
        ];
    }

    /**
     * @test
     */
    public function I_can_not_get_secondary_property_on_non_1d6_roll()
    {
        $this->expectException(\DrdPlus\Tables\History\Exceptions\UnexpectedDiceRoll::class);
        $this->expectExceptionMessageRegExp('~0~');
        (new InfluenceOfFortuneTable())->getSecondaryPropertyOnFate(FateCode::getIt(FateCode::EXCEPTIONAL_PROPERTIES), 0);
    }
}