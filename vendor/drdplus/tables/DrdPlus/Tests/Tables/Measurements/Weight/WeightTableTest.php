<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Measurements\Weight;

use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tables\Measurements\Weight\Weight;
use DrdPlus\Tables\Measurements\Weight\WeightBonus;
use DrdPlus\Tables\Measurements\Weight\WeightTable;
use DrdPlus\Tests\Tables\Measurements\MeasurementTableTest;

class WeightTableTest extends MeasurementTableTest
{

    /**
     * @test
     */
    public function I_can_get_header()
    {
        $weightTable = new WeightTable();
        self::assertSame([['bonus', 'kg']], $weightTable->getHeader());
    }

    /**
     * @test
     */
    public function I_can_convert_bonus_to_value()
    {
        $weightTable = new WeightTable();
        self::assertSame(
            0.1,
            $weightTable->toWeight(new WeightBonus(-40, $weightTable))->getValue()
        );
        self::assertSame(
            10.0,
            $weightTable->toWeight(new WeightBonus(0, $weightTable))->getValue()
        );
        self::assertSame(
            9000.0,
            $weightTable->toWeight(new WeightBonus(59, $weightTable))->getValue()
        );
    }

    /**
     * @test
     * @expectedException \OutOfRangeException
     */
    public function I_can_not_use_too_low_bonus_to_value()
    {
        $weightTable = new WeightTable();
        $weightTable->toWeight(new WeightBonus(-41, $weightTable));
    }

    /**
     * @test
     * @expectedException \OutOfRangeException
     */
    public function I_can_not_convert_too_high_bonus_into_too_detailed_unit()
    {
        $weightTable = new WeightTable();
        $weightTable->toWeight(new WeightBonus(60, $weightTable));
    }

    /**
     * @test
     */
    public function I_can_convert_value_to_bonus()
    {
        $weightTable = new WeightTable();
        self::assertSame(-40, $weightTable->toBonus(new Weight(0.1, Weight::KG, $weightTable))->getValue());

        self::assertSame(0, $weightTable->toBonus(new Weight(10, Weight::KG, $weightTable))->getValue());

        self::assertSame(20, $weightTable->toBonus(new Weight(104, Weight::KG, $weightTable))->getValue()); // 20 is the closest bonus
        self::assertSame(21, $weightTable->toBonus(new Weight(105, Weight::KG, $weightTable))->getValue()); // 20 and 21 are closest bonuses, 21 is taken because higher
        self::assertSame(21, $weightTable->toBonus(new Weight(106, Weight::KG, $weightTable))->getValue()); // 21 is the closest bonus (higher in this case)

        self::assertSame(59, $weightTable->toBonus(new Weight(9000, Weight::KG, $weightTable))->getValue());
    }

    /**
     * @test
     * @expectedException \OutOfRangeException
     */
    public function I_can_not_convert_too_low_value_to_bonus()
    {
        $weightTable = new WeightTable();
        $weightTable->toBonus(new Weight(0.09, Weight::KG, $weightTable));
    }

    /**
     * @test
     * @expectedException \OutOfRangeException
     */
    public function I_can_not_convert_too_high_value_to_bonus()
    {
        $weightTable = new WeightTable();
        $weightTable->toBonus(new Weight(9001, Weight::KG, $weightTable))->getValue();
    }

    /**
     * @test
     */
    public function I_can_convert_simplified_weight_bonus_to_bonus()
    {
        $weightTable = new WeightTable();
        $bonus = $weightTable->getBonusFromSimplifiedBonus($value = 123);
        self::assertSame($value + 12, $bonus->getValue());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     * @expectedExceptionMessageRegExp ~tiny~
     */
    public function I_can_not_convert_non_number_as_simplified_weight_bonus_to_bonus()
    {
        $weightTable = new WeightTable();
        $weightTable->getBonusFromSimplifiedBonus('tiny');
    }

    /**
     * @test
     * @dataProvider provideStrengthCargoWeightBonusAndExpectedMalus
     * @param int $strength
     * @param int $weightBonus
     * @param int $expectedMalus
     */
    public function I_can_get_malus_from_load_for_missing_strength($strength, $weightBonus, $expectedMalus)
    {
        self::assertSame(
            $expectedMalus,
            (new WeightTable())->getMalusFromLoad($this->createStrength($strength), $this->createWeight($weightBonus))
        );
    }

    public function provideStrengthCargoWeightBonusAndExpectedMalus()
    {
        return [
            [1, 2, -1],
            [0, 0, 0],
            [10, 10, 0],
            [-5, 12, -9],
            [45, 1, 0], // malus is zero at most, never becomes positive number (bonus)
        ];
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|Strength
     */
    private function createStrength($value)
    {
        $strength = $this->mockery(Strength::class);
        $strength->shouldReceive('getValue')
            ->andReturn($value);

        return $strength;
    }

    /**
     * @param int $bonusValue
     * @return Weight|\Mockery\MockInterface
     */
    private function createWeight($bonusValue)
    {
        $weight = $this->mockery(Weight::class);
        $weight->shouldReceive('getBonus')
            ->andReturn($weightBonus = $this->mockery(WeightBonus::class));
        $weightBonus->shouldReceive('getValue')
            ->andReturn($bonusValue);

        return $weight;
    }
}
