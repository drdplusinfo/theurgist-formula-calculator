<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Measurements\Distance;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tests\Tables\Measurements\MeasurementTableTest;
use Granam\Integer\IntegerObject;

class DistanceTableTest extends MeasurementTableTest
{
    /**
     * @test
     */
    public function I_can_convert_bonus_to_value()
    {
        $distanceTable = new DistanceTable();

        $bonus = new DistanceBonus(-40, $distanceTable);
        $distance = $distanceTable->toDistance($bonus);
        self::assertSame(0.01, $distance->getMeters());
        self::assertSame(0.00001, $distance->getKilometers());
        self::assertSame($bonus->getValue(), $distance->getBonus()->getValue());

        $bonus = new DistanceBonus(0, $distanceTable);
        $distance = $distanceTable->toDistance($bonus);
        self::assertSame(1.0, $distance->getMeters());
        self::assertSame(0.001, $distance->getKilometers());
        self::assertSame($bonus->getValue(), $distance->getBonus()->getValue());

        $bonus = new DistanceBonus(119, $distanceTable);
        $distance = $distanceTable->toDistance($bonus);
        self::assertSame(900000.0, $distance->getMeters());
        self::assertSame(900.0, $distance->getKilometers());
        self::assertSame($bonus->getValue(), $distance->getBonus()->getValue());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     */
    public function I_can_not_use_too_low_bonus_to_value()
    {
        $distanceTable = new DistanceTable();
        $distanceTable->toDistance(new DistanceBonus(-41, $distanceTable));
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     */
    public function I_can_not_convert_too_high_bonus_into_too_detailed_unit()
    {
        $distanceTable = new DistanceTable();
        $distanceTable->toDistance(new DistanceBonus(120, $distanceTable));
    }

    /**
     * @test
     */
    public function I_can_convert_value_to_bonus()
    {
        $distanceTable = new DistanceTable();

        // 0.01 matches more bonuses - the lowest is taken
        $distance = new Distance(0.01, DistanceUnitCode::METER, $distanceTable);
        self::assertSame(-40, $distance->getBonus()->getValue());

        $distance = new Distance(1, DistanceUnitCode::METER, $distanceTable);
        self::assertSame(0, $distance->getBonus()->getValue());
        $distance = new Distance(1.5, DistanceUnitCode::METER, $distanceTable);
        self::assertSame(4, $distance->getBonus()->getValue());

        $distance = new Distance(104, DistanceUnitCode::METER, $distanceTable);
        self::assertSame(40, $distance->getBonus()->getValue()); // 40 is the closest bonus
        $distance = new Distance(105, DistanceUnitCode::METER, $distanceTable);
        self::assertSame(41, $distance->getBonus()->getValue()); // 40 and 41 are closest bonuses, 41 is taken because higher
        $distance = new Distance(106, DistanceUnitCode::METER, $distanceTable);
        self::assertSame(41, $distance->getBonus()->getValue()); // 41 is the closest bonus (higher in this case)

        $distance = new Distance(900, DistanceUnitCode::KILOMETER, $distanceTable);
        self::assertSame(119, $distance->getBonus()->getValue());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange
     */
    public function I_can_not_convert_too_low_value_to_bonus()
    {
        $distanceTable = new DistanceTable();
        $distance = new Distance(0.009, DistanceUnitCode::METER, $distanceTable);
        $distance->getBonus();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange
     */
    public function I_can_not_convert_too_high_value_to_bonus()
    {
        $distanceTable = new DistanceTable();
        $distance = new Distance(901, DistanceUnitCode::KILOMETER, $distanceTable);
        $distance->getBonus();
    }

    /**
     * @test
     */
    public function I_can_convert_size_to_bonus()
    {
        $weightTable = new DistanceTable();
        $bonus = $weightTable->sizeToDistanceBonus(new IntegerObject($value = 123));
        self::assertSame($value + 12, $bonus->getValue());
    }
}