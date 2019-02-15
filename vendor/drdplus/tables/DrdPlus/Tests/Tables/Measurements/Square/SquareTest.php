<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Square;

use DrdPlus\Codes\Units\SquareUnitCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Measurements\Square\Square;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfMeasurement;
use Granam\String\StringTools;

class SquareTest extends AbstractTestOfMeasurement
{
    protected function getTableClass(): string
    {
        return DistanceTable::class;
    }

    protected function getDefaultUnit(): string
    {
        return SquareUnitCode::SQUARE_METER;
    }

    public function getAllUnits(): array
    {
        return SquareUnitCode::getPossibleValues();
    }

    /**
     * @test
     */
    public function I_can_get_unit_as_a_code_instance(): void
    {
        $distanceTable = Tables::getIt()->getDistanceTable();
        foreach ($this->getAllUnits() as $unitName) {
            $square = new Square(123.456, $unitName, $distanceTable);
            self::assertSame(SquareUnitCode::getIt($unitName), $square->getUnitCode());
        }
    }

    /**
     * @test
     */
    public function I_can_get_it_in_every_unit_by_specific_getter(): void
    {
        $distanceTable = Tables::getIt()->getDistanceTable();

        $squareDecimeterToSquareMeter = 10 ** -2;
        $squareMeterToSquareKilometer = 10 ** -6;
        $squareDecimeterToSquareKilometer = $squareDecimeterToSquareMeter * $squareMeterToSquareKilometer;

        $distanceDecimeters = new Distance(10, Distance::DECIMETER, $distanceTable);
        $squareDecimeters = new Square($value = $distanceDecimeters->getValue() ** 2, $unit = Square::SQUARE_DECIMETER, $distanceTable);
        self::assertSame((float)$value, $squareDecimeters->getValue());
        self::assertSame($unit, $squareDecimeters->getUnit());
        self::assertSame((float)$value * $squareDecimeterToSquareMeter, $squareDecimeters->getSquareMeters());
        self::assertSame((float)($value * $squareDecimeterToSquareKilometer), $squareDecimeters->getSquareKilometers());
        self::assertSame(
            $distanceDecimeters->getBonus()->getValue() * 2,
            $squareDecimeters->getBonus()->getValue(),
            "Expected different bonus for square {$squareDecimeters}"
        );

        $distanceMeters = new Distance(456, Distance::METER, $distanceTable);
        $squareMeters = new Square($value = $distanceMeters->getValue() ** 2, $unit = Square::SQUARE_METER, $distanceTable);
        self::assertSame((float)$value, $squareMeters->getValue());
        self::assertSame($unit, $squareMeters->getUnit());
        self::assertSame((float)$value / $squareDecimeterToSquareMeter, $squareMeters->getSquareDecimeters());
        self::assertSame((float)$value, $squareMeters->getSquareMeters());
        self::assertSame((float)($value * $squareMeterToSquareKilometer), $squareMeters->getSquareKilometers());
        self::assertSame($distanceMeters->getBonus()->getValue() * 2, $squareMeters->getBonus()->getValue());

        $distanceKilometers = new Distance(900, Distance::KILOMETER, $distanceTable);
        $squareKilometers = new Square($value = $distanceKilometers->getValue() ** 2, $unit = SquareUnitCode::SQUARE_KILOMETER, $distanceTable);
        self::assertSame($value, $squareKilometers->getValue());
        self::assertSame($unit, $squareKilometers->getUnit());
        self::assertSame($value, $squareKilometers->getSquareKilometers());
        self::assertSame(round($value / $squareMeterToSquareKilometer), $squareKilometers->getSquareMeters());
        self::assertSame(round($value / $squareDecimeterToSquareKilometer), $squareKilometers->getSquareDecimeters());
        self::assertSame(
            $distanceKilometers->getBonus()->getValue() * 2,
            $squareKilometers->getBonus()->getValue(),
            "Expected different bonus for square {$squareKilometers}"
        );
    }

    /**
     * @test
     * @dataProvider provideInSpecificUnitGetters
     * @expectedException \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     * @expectedExceptionMessageRegExp ~drop~
     * @param string $getInUnit
     */
    public function Can_not_cast_it_from_unknown_unit(string $getInUnit): void
    {
        /** @var Square|\Mockery\MockInterface $squareWithInvalidUnit */
        $squareWithInvalidUnit = $this->mockery(Square::class);
        $squareWithInvalidUnit->shouldReceive('getUnit')
            ->andReturn('drop');
        $squareWithInvalidUnit->makePartial();
        $squareWithInvalidUnit->$getInUnit();
    }

    public function provideInSpecificUnitGetters(): array
    {
        $getters = [];
        foreach (SquareUnitCode::getPossibleValues() as $squareUnit) {
            // like getMeters
            $getters[] = [StringTools::assembleGetterForName($squareUnit . 's' /* plural */)];
        }

        return $getters;
    }

    /**
     * @test
     * @dataProvider provideSquareUnits
     * @expectedException \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     * @expectedExceptionMessageRegExp ~first~
     * @param string $unit
     * @throws \ReflectionException
     */
    public function Can_not_cast_it_to_unknown_unit(string $unit): void
    {
        $square = new \ReflectionClass(Square::class);
        $getValueInDifferentUnit = $square->getMethod('getValueInDifferentUnit');
        $getValueInDifferentUnit->setAccessible(true);
        $getValueInDifferentUnit->invoke(new Square(123, $unit, Tables::getIt()->getDistanceTable()), 'first');
    }

    public function provideSquareUnits()
    {
        return array_map(
            function (string $squareUnit) {
                return [$squareUnit]; // just wrapped by an array to satisfy required PHPUnit format
            },
            SquareUnitCode::getPossibleValues()
        );
    }

    /**
     * @test
     */
    public function I_can_convert_value_to_bonus(): void
    {
        $distanceTable = Tables::getIt()->getDistanceTable();

        $distance = new Distance(0.01, Distance::METER, $distanceTable);
        $square = new Square($distance->getValue() ** 2, Square::SQUARE_METER, $distanceTable);
        self::assertSame($distance->getBonus()->getValue() * 2, $square->getBonus()->getValue());

        $distance = new Distance(1, Distance::METER, $distanceTable);
        $square = new Square($distance->getValue() ** 2, Square::SQUARE_METER, $distanceTable);
        self::assertSame($distance->getBonus()->getValue() * 2, $square->getBonus()->getValue());
        $distance = new Distance(1.5, Distance::METER, $distanceTable);
        $square = new Square($distance->getValue() ** 2, Square::SQUARE_METER, $distanceTable);
        self::assertSame($distance->getBonus()->getValue() * 2, $square->getBonus()->getValue());

        $distance = new Distance(104, Distance::METER, $distanceTable);
        $square = new Square($distance->getValue() ** 2, Square::SQUARE_METER, $distanceTable);
        self::assertSame($distance->getBonus()->getValue() * 2, $square->getBonus()->getValue());
        $distance = new Distance(105, Distance::METER, $distanceTable);
        $square = new Square($distance->getValue() ** 2, Square::SQUARE_METER, $distanceTable);
        self::assertSame($distance->getBonus()->getValue() * 2, $square->getBonus()->getValue());
        $distance = new Distance(106, Distance::METER, $distanceTable);
        $square = new Square($distance->getValue() ** 2, Square::SQUARE_METER, $distanceTable);
        self::assertSame($distance->getBonus()->getValue() * 2, $square->getBonus()->getValue());

        $distance = new Distance(300, Distance::KILOMETER, $distanceTable);
        $square = new Square($distance->getValue() ** 2, Square::SQUARE_KILOMETER, $distanceTable);
        self::assertSame($distance->getBonus()->getValue() * 2, $square->getBonus()->getValue(), "Expected different bonus for square {$square}");
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange
     */
    public function I_can_not_convert_too_low_value_to_bonus(): void
    {
        $distance = new Square(0.01 /* minimal distance with known bonus */ ** 2 /* power of two to get square */ - 1 /* out of range */, SquareUnitCode::SQUARE_METER, Tables::getIt()->getDistanceTable());
        $distance->getBonus();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange
     */
    public function I_can_not_convert_too_high_value_to_bonus(): void
    {
        $distance = new Square(900 /* maximal distance with known bonus */ ** 2 /* power of two to get square */ + 1 /* out of range */, SquareUnitCode::SQUARE_KILOMETER, Tables::getIt()->getDistanceTable());
        $distance->getBonus();
    }
}