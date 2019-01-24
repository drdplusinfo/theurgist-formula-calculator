<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Measurements\Weight;

use DrdPlus\Codes\Units\WeightUnitCode;
use DrdPlus\Tables\Measurements\Weight\Weight;
use DrdPlus\Tables\Measurements\Weight\WeightTable;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfMeasurement;

class WeightTest extends AbstractTestOfMeasurement
{

    /**
     * @return string
     */
    protected function getDefaultUnit(): string
    {
        return Weight::KG;
    }

    /**
     * @test
     */
    public function I_can_get_explicitly_kilograms()
    {
        $weight = new Weight(123.876, Weight::KG, new WeightTable());
        self::assertSame(123.876, $weight->getKilograms());
    }

    /**
     * @test
     */
    public function I_can_get_unit_as_code()
    {
        $meters = new Weight(123, WeightUnitCode::KG, new WeightTable());
        self::assertSame(WeightUnitCode::getIt(WeightUnitCode::KG), $meters->getUnitCode());
    }
}