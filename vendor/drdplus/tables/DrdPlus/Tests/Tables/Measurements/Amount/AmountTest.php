<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Amount;

use DrdPlus\Tables\Measurements\Amount\Amount;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfMeasurement;

class AmountTest extends AbstractTestOfMeasurement
{
    /**
     * @test
     */
    public function I_can_get_it_by_factory_method(): void
    {
        self::assertEquals(new Amount(123, Amount::AMOUNT, Tables::getIt()->getAmountTable()), Amount::getIt(123, Tables::getIt()));
    }
}
