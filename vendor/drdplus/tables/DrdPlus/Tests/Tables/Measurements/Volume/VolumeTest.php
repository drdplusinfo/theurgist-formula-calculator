<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Measurements\Volume;

use DrdPlus\Codes\Units\VolumeUnitCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Measurements\Volume\Volume;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfMeasurement;
use Granam\String\StringTools;

class VolumeTest extends AbstractTestOfMeasurement
{
    protected function getTableClass(): string
    {
        return DistanceTable::class;
    }

    protected function getDefaultUnit(): string
    {
        return VolumeUnitCode::CUBIC_METER;
    }

    public function getAllUnits(): array
    {
        return VolumeUnitCode::getPossibleValues();
    }

    /**
     * @test
     */
    public function I_can_get_unit_as_a_code_instance(): void
    {
        $distanceTable = Tables::getIt()->getDistanceTable();
        foreach ($this->getAllUnits() as $unitName) {
            $volume = new Volume(123.456, $unitName, $distanceTable);
            self::assertSame(VolumeUnitCode::getIt($unitName), $volume->getUnitCode());
        }
    }

    /**
     * @test
     */
    public function I_can_get_it_in_every_unit_by_specific_getter(): void
    {
        $distanceTable = Tables::getIt()->getDistanceTable();

        $literToCubicMeter = 10 ** -3;
        $cubicMeterToCubicKilometer = 10 ** -9;
        $literToCubicKilometer = $literToCubicMeter * $cubicMeterToCubicKilometer;

        $liters = new Volume($value = 10, $unit = VolumeUnitCode::LITER, $distanceTable);
        self::assertSame((float)$value, $liters->getValue());
        self::assertSame($unit, $liters->getUnit());
        self::assertSame((float)$value * $literToCubicMeter, $liters->getCubicMeters());
        self::assertSame((float)($value * $literToCubicKilometer), $liters->getCubicKilometers());
        self::assertSame(-48, $liters->getBonus()->getValue());

        $meters = new Volume($value = 456, $unit = VolumeUnitCode::CUBIC_METER, $distanceTable);
        self::assertSame((float)$value, $meters->getValue());
        self::assertSame($unit, $meters->getUnit());
        self::assertSame((float)$value / $literToCubicMeter, $meters->getLiters());
        self::assertSame((float)$value, $meters->getCubicMeters());
        self::assertSame((float)($value * $cubicMeterToCubicKilometer), $meters->getCubicKilometers());
        self::assertSame(54, $meters->getBonus()->getValue());

        $kilometers = new Volume($value = 1.0, $unit = VolumeUnitCode::CUBIC_KILOMETER, $distanceTable);
        self::assertSame($value, $kilometers->getValue());
        self::assertSame($unit, $kilometers->getUnit());
        self::assertSame($value, $kilometers->getCubicKilometers());
        self::assertSame(round($value / $cubicMeterToCubicKilometer), $kilometers->getCubicMeters());
        self::assertSame(round($value / $literToCubicKilometer), $kilometers->getLiters());
        self::assertSame(180, $kilometers->getBonus()->getValue());
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
        /** @var Volume|\Mockery\MockInterface $volumeWithInvalidUnit */
        $volumeWithInvalidUnit = $this->mockery(Volume::class);
        $volumeWithInvalidUnit->shouldReceive('getUnit')
            ->andReturn('drop');
        $volumeWithInvalidUnit->makePartial();
        $volumeWithInvalidUnit->$getInUnit();
    }

    public function provideInSpecificUnitGetters(): array
    {
        $getters = [];
        foreach (VolumeUnitCode::getPossibleValues() as $volumeUnit) {
            // like getMeters
            $getters[] = [StringTools::assembleGetterForName($volumeUnit . 's' /* plural */)];
        }

        return $getters;
    }

    /**
     * @test
     * @dataProvider provideVolumeUnits
     * @expectedException \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     * @expectedExceptionMessageRegExp ~first~
     * @param string $unit
     * @throws \ReflectionException
     */
    public function Can_not_cast_it_to_unknown_unit(string $unit): void
    {
        $volume = new \ReflectionClass(Volume::class);
        $getValueInDifferentUnit = $volume->getMethod('getValueInDifferentUnit');
        $getValueInDifferentUnit->setAccessible(true);
        $getValueInDifferentUnit->invoke(new Volume(123, $unit, Tables::getIt()->getDistanceTable()), 'first');
    }

    public function provideVolumeUnits(): array
    {
        return array_map(
            function (string $volumeUnit) {
                return [$volumeUnit]; // just wrapped by an array to satisfy required PHPUnit format
            },
            VolumeUnitCode::getPossibleValues()
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange
     */
    public function I_can_not_convert_too_low_value_to_bonus(): void
    {
        $volume = new Volume(0.000000009, VolumeUnitCode::CUBIC_METER, Tables::getIt()->getDistanceTable());
        $volume->getBonus();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange
     */
    public function I_can_not_convert_too_high_value_to_bonus(): void
    {
        $volume = new Volume(9999999999, VolumeUnitCode::CUBIC_KILOMETER, Tables::getIt()->getDistanceTable());
        $volume->getBonus();
    }

    /**
     * @test
     * @link https://pph.drdplus.info/#vypocet_objemu_petilitroveho_korbele_v_prikladu
     */
    public function Liters_to_meters_match_expected_conversion(): void
    {
        $distance = new Distance(10, Distance::METER, Tables::getIt()->getDistanceTable());
        self::assertSame(20, $distance->getBonus()->getValue());
        $cubicMetersBonusValue = $distance->getBonus()->getValue() - 60;
        $cubicMetersAsDistanceBonus = new DistanceBonus($cubicMetersBonusValue, Tables::getIt()->getDistanceTable());
        self::assertSame(0.01, $cubicMetersAsDistanceBonus->getDistance(Distance::METER)->getValue());
    }
}