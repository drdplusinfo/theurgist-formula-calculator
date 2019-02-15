<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Amount;

use DrdPlus\Tables\Measurements\Amount\Amount;
use DrdPlus\Tables\Measurements\Amount\AmountBonus;
use DrdPlus\Tables\Measurements\Amount\AmountTable;
use DrdPlus\Tests\Tables\Measurements\MeasurementTableTest;

class AmountTableTest extends MeasurementTableTest
{

    /**
     * @test
     */
    public function I_can_get_header()
    {
        $amountTable = new AmountTable();
        self::assertEquals([['bonus', 'amount']], $amountTable->getHeader());
    }

    /**
     * @test
     */
    public function I_can_convert_bonus_to_value()
    {
        $amountTable = new AmountTable();
        $maxAttempts = 10000;
        $attempt = 1;
        do {
            $zeroOrOne = $amountTable->toAmount(new AmountBonus(-20, $amountTable));
            if ($zeroOrOne->getValue() === 1) {
                break;
            }
        } while ($attempt++ < $maxAttempts);
        self::assertLessThan($maxAttempts, $attempt);
        self::assertSame(1, $zeroOrOne->getValue());
        self::assertSame(
            1,
            $amountTable->toAmount(new AmountBonus(0, $amountTable))->getValue()
        );
        self::assertSame(
            90000,
            $amountTable->toAmount(new AmountBonus(99, $amountTable))->getValue()
        );
    }

    /**
     * @test
     * @expectedException \OutOfRangeException
     */
    public function I_can_not_use_too_low_bonus_to_value()
    {
        $amountTable = new AmountTable();
        $amountTable->toAmount(new AmountBonus(-21, $amountTable));
    }

    /**
     * @test
     * @expectedException \OutOfRangeException
     */
    public function I_can_not_convert_too_high_bonus_into_too_detailed_unit()
    {
        $amountTable = new AmountTable();
        $amountTable->toAmount(new AmountBonus(100, $amountTable));
    }

    /**
     * @test
     */
    public function I_can_convert_value_to_bonus()
    {
        $amountTable = new AmountTable();
        self::assertSame(
            0,
            $amountTable->toBonus(new Amount(1, Amount::AMOUNT, $amountTable))->getValue()
        );

        self::assertSame(
            40,
            $amountTable->toBonus(new Amount(104, Amount::AMOUNT, $amountTable))->getValue()
        ); // 40 is the closest bonus (lower in this case)
        self::assertSame(
            41,
            $amountTable->toBonus(new Amount(105, Amount::AMOUNT, $amountTable))->getValue()
        ); // 40 and 41 are closest bonuses, 41 is taken because higher
        self::assertSame(
            41,
            $amountTable->toBonus(new Amount(106, Amount::AMOUNT, $amountTable))->getValue()
        ); // 41 is the closest bonus (higher in this case)

        self::assertSame(
            99,
            $amountTable->toBonus(new Amount(90000, Amount::AMOUNT, $amountTable))->getValue()
        );
    }

    /**
     * @test
     * @expectedException \OutOfRangeException
     */
    public function I_can_not_convert_too_low_value_to_bonus()
    {
        $amountTable = new AmountTable();
        $amountTable->toBonus(new Amount(0, Amount::AMOUNT, $amountTable));
    }

    /**
     * @test
     * @expectedException \OutOfRangeException
     */
    public function I_can_not_convert_too_high_value_to_bonus()
    {
        $amountTable = new AmountTable();
        $amountTable->toBonus(new Amount(90001, Amount::AMOUNT, $amountTable));
    }
}