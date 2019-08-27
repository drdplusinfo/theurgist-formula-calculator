<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Distance;

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
     */
    public function I_can_not_use_too_low_bonus_to_value()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus::class);
        $distanceTable = new DistanceTable();
        $distanceTable->toDistance(new DistanceBonus(-41, $distanceTable));
    }

    /**
     * @test
     */
    public function I_can_not_convert_too_high_bonus_into_too_detailed_unit()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus::class);
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
        $distance = new Distance(0.01, Distance::METER, $distanceTable);
        self::assertSame(-40, $distance->getBonus()->getValue());

        $distance = new Distance(1, Distance::METER, $distanceTable);
        self::assertSame(0, $distance->getBonus()->getValue());
        $distance = new Distance(1.5, Distance::METER, $distanceTable);
        self::assertSame(4, $distance->getBonus()->getValue());

        $distance = new Distance(104, Distance::METER, $distanceTable);
        self::assertSame(40, $distance->getBonus()->getValue()); // 40 is the closest bonus
        $distance = new Distance(105, Distance::METER, $distanceTable);
        self::assertSame(41, $distance->getBonus()->getValue()); // 40 and 41 are closest bonuses, 41 is taken because higher
        $distance = new Distance(106, Distance::METER, $distanceTable);
        self::assertSame(41, $distance->getBonus()->getValue()); // 41 is the closest bonus (higher in this case)

        $distance = new Distance(900, Distance::KILOMETER, $distanceTable);
        self::assertSame(119, $distance->getBonus()->getValue());
    }

    /**
     * @test
     */
    public function I_can_not_convert_too_low_value_to_bonus()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange::class);
        $distanceTable = new DistanceTable();
        $distance = new Distance(0.009, Distance::METER, $distanceTable);
        $distance->getBonus();
    }

    /**
     * @test
     */
    public function I_can_not_convert_too_high_value_to_bonus()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange::class);
        $distanceTable = new DistanceTable();
        $distance = new Distance(901, Distance::KILOMETER, $distanceTable);
        $distance->getBonus();
    }

    /**
     * @test
     */
    public function I_can_convert_size_to_bonus()
    {
        $distanceTable = new DistanceTable();
        $bonus = $distanceTable->sizeToDistanceBonus(new IntegerObject($value = 123));
        self::assertSame($value + 12, $bonus->getValue());
    }

    /**
     * @test
     */
    public function I_can_get_bonus_for_every_distance_in_decimeters_if_have_bonus_for_same_distance_in_meters()
    {
        $distanceTable = new DistanceTable();
        foreach ($distanceTable->getIndexedValues() as $bonus => $distances) {
            if ($bonus < 0) {
                continue; // more than a single bonus can match for low values an we do not want to check them
            }
            if (\array_key_exists(Distance::METER, $distances)) {
                $distanceInDecimeters = new Distance($distances[Distance::METER] * 10, Distance::DECIMETER, $distanceTable);
                self::assertSame(
                    $bonus,
                    $distanceInDecimeters->getBonus()->getValue(),
                    'Expected different bonus for distance of ' . $distanceInDecimeters
                );
            }
        }
    }
}