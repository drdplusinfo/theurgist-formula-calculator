<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Distance;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfMeasurement;
use Granam\String\StringTools;

class DistanceTest extends AbstractTestOfMeasurement
{

    protected function getDefaultUnit(): string
    {
        return DistanceUnitCode::METER;
    }

    public function getAllUnits(): array
    {
        return DistanceUnitCode::getPossibleValues();
    }

    /**
     * @test
     */
    public function I_can_get_it_in_every_unit_by_specific_getter(): void
    {
        $distanceTable = new DistanceTable();

        $inDm = new Distance($value = 10, $unit = DistanceUnitCode::DECIMETER, $distanceTable);
        self::assertSame((float)$value, $inDm->getValue());
        self::assertSame($unit, $inDm->getUnit());
        self::assertSame((float)$value / 10, $inDm->getMeters());
        self::assertSame((float)($value / 10000), $inDm->getKilometers());
        self::assertSame(0, $inDm->getBonus()->getValue());

        $inM = new Distance($value = 456, $unit = DistanceUnitCode::METER, $distanceTable);
        self::assertSame((float)$value, $inM->getValue());
        self::assertSame($unit, $inM->getUnit());
        self::assertSame((float)$value * 10, $inM->getDecimeters());
        self::assertSame((float)$value, $inM->getMeters());
        self::assertSame((float)($value / 1000), $inM->getKilometers());
        self::assertSame(53, $inM->getBonus()->getValue());

        $inKm = new Distance($value = 123, $unit = DistanceUnitCode::KILOMETER, $distanceTable);
        self::assertSame((float)$value, $inKm->getValue());
        self::assertSame($unit, $inKm->getUnit());
        self::assertSame((float)$value, $inKm->getKilometers());
        self::assertSame((float)($value * 1000), $inKm->getMeters());
        self::assertSame((float)($value * 10000), $inKm->getDecimeters());
        self::assertSame(102, $inKm->getBonus()->getValue());

        $inLightYears = new Distance($value = 1, $unit = DistanceUnitCode::LIGHT_YEAR, $distanceTable);
        self::assertSame((float)$value, $inLightYears->getValue());
        self::assertSame((float)$value, $inLightYears->getLightYears());
        self::assertSame($unit, $inLightYears->getUnit());
        self::assertSame(319, $inLightYears->getBonus()->getValue());
    }

    /**
     * @test
     * @dataProvider provideInSpecificUnitGetters
     * @param string $getInUnit
     */
    public function Can_not_cast_it_from_unknown_unit(string $getInUnit): void
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Exceptions\UnknownUnit::class);
        $this->expectExceptionMessageRegExp('~megastep~');
        /** @var Distance|\Mockery\MockInterface $distanceWithInvalidUnit */
        $distanceWithInvalidUnit = $this->mockery(Distance::class);
        $distanceWithInvalidUnit->shouldReceive('getUnit')
            ->andReturn('megastep');
        $distanceWithInvalidUnit->makePartial();
        $distanceWithInvalidUnit->$getInUnit();
    }

    public function provideInSpecificUnitGetters(): array
    {
        $getters = [];
        foreach (DistanceUnitCode::getPossibleValues() as $distanceUnit) {
            // like getMeters
            $getters[] = [StringTools::assembleGetterForName($distanceUnit . 's' /* plural */)];
        }

        return $getters;
    }

    /**
     * @test
     * @dataProvider provideDistanceUnits
     * @param string $unit
     * @throws \ReflectionException
     */
    public function Can_not_cast_it_to_unknown_unit(string $unit): void
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Exceptions\UnknownUnit::class);
        $this->expectExceptionMessageRegExp('~nanoinch~');
        $distance = new \ReflectionClass(Distance::class);
        $getValueInDifferentUnit = $distance->getMethod('getValueInDifferentUnit');
        $getValueInDifferentUnit->setAccessible(true);
        $getValueInDifferentUnit->invoke(new Distance(123, $unit, new DistanceTable()), 'nanoinch');
    }

    public function provideDistanceUnits(): array
    {
        return array_map(
            function (string $distanceUnit) {
                return [$distanceUnit]; // just wrapped by an array to satisfy required PHPUnit format
            },
            DistanceUnitCode::getPossibleValues()
        );
    }

    /**
     * @test
     */
    public function I_can_get_unit_as_a_code_instance(): void
    {
        $distanceTable = new DistanceTable();

        foreach ($this->getAllUnits() as $unitName) {
            $distance = new Distance(123.456, $unitName, $distanceTable);
            self::assertSame(DistanceUnitCode::getIt($unitName), $distance->getUnitCode());
        }
    }

}