<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Measurements\Price;

use DrdPlus\Tables\Measurements\Price\Price;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfMeasurement;

class PriceTest extends AbstractTestOfMeasurement
{

    protected function getDefaultUnit(): string
    {
        return Price::COPPER_COIN;
    }

    public function getAllUnits(): array
    {
        return [Price::COPPER_COIN, Price::SILVER_COIN, Price::GOLD_COIN];
    }

    /**
     * @test
     */
    public function I_can_convert_money_per_base()
    {
        $coppers = new Price($value = 123, Price::COPPER_COIN);
        self::assertSame((float)$value, $coppers->getCopperCoins());
        self::assertSame($value / 10, $coppers->getSilverCoins());
        self::assertSame($value / 100, $coppers->getGoldCoins());

        $silvers = new Price($value = 123, Price::SILVER_COIN);
        self::assertSame($value * 10.0, $silvers->getCopperCoins());
        self::assertSame((float)$value, $silvers->getSilverCoins());
        self::assertSame($value / 10, $silvers->getGoldCoins());

        $golds = new Price($value = 123, Price::GOLD_COIN);
        self::assertSame($value * 100.0, $golds->getCopperCoins());
        self::assertSame($value * 10.0, $golds->getSilverCoins());
        self::assertSame((float)$value, $golds->getGoldCoins());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    public function Broken_unit_check_is_detected_on_cast_to_copper()
    {
        $price = new BrokenPriceMeasurement(123, 'non-existing unit');
        $price->getCopperCoins();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    public function Broken_unit_check_is_detected_on_cast_to_silver()
    {
        $price = new BrokenPriceMeasurement(123, 'non-existing unit');
        $price->getSilverCoins();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    public function Broken_unit_check_is_detected_on_cast_to_gold()
    {
        $price = new BrokenPriceMeasurement(123, 'non-existing unit');
        $price->getGoldCoins();
    }

}

/** inner */
class BrokenPriceMeasurement extends Price
{
    protected function checkUnit(string $unit): void
    {
    }
}