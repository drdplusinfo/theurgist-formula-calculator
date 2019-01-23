<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Measurements\Volume;

use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Measurements\Volume\VolumeBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfBonus;

class VolumeBonusTest extends AbstractTestOfBonus
{
    protected function getTableClass(): string
    {
        return DistanceTable::class;
    }

    /**
     * @test
     */
    public function I_can_convert_bonus_to_value(): void
    {
        $cubicKilometerToMeter = 1000 ** 3;
        $cubicKilometerToLiter = 1000 ** 4;
        $volumeBonus = new VolumeBonus(-40, Tables::getIt()->getDistanceTable());
        $volume = $volumeBonus->getVolume();
        self::assertSame(0.008, $volume->getCubicMeters());
        self::assertSame(0.008 / $cubicKilometerToMeter, $volume->getCubicKilometers());

        $volumeBonus = new VolumeBonus(0, Tables::getIt()->getDistanceTable());
        $volume = $volumeBonus->getVolume();
        self::assertSame(1.0, $volume->getCubicMeters());
        self::assertSame(1.0 / $cubicKilometerToMeter, $volume->getCubicKilometers());
        self::assertSame($volumeBonus->getValue(), $volume->getBonus()->getValue());

        $volumeBonus = new VolumeBonus(119, Tables::getIt()->getDistanceTable());
        $volume = $volumeBonus->getVolume();
        self::assertSame(0.001 * $cubicKilometerToLiter, $volume->getLiters());
        self::assertSame(0.001 * $cubicKilometerToMeter, $volume->getCubicMeters());
        self::assertSame(0.001, $volume->getCubicKilometers());
    }

    /**
     * @test
     */
    public function I_can_convert_it_to_value_from_every_bonus_and_back(): void
    {
        $distanceTable = Tables::getIt()->getDistanceTable();
        foreach (\range(-10, 120) as $bonusValue) {
            $volumeBonus = new VolumeBonus($bonusValue, $distanceTable);
            self::assertSame($bonusValue, $volumeBonus->getValue());
            $volume = $volumeBonus->getVolume();
            $volumeBonusAgain = $volume->getBonus();
            self::assertGreaterThanOrEqual($bonusValue - 1 /* tolerance */, $volumeBonusAgain->getValue());
            self::assertLessThanOrEqual($bonusValue + 1 /* tolerance */, $volumeBonusAgain->getValue());
        }
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     */
    public function I_can_not_use_too_low_bonus_to_value(): void
    {
        (new VolumeBonus(-999, Tables::getIt()->getDistanceTable()))->getVolume();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     */
    public function I_can_not_convert_too_high_bonus_into_too_detailed_unit(): void
    {
        (new VolumeBonus(999, Tables::getIt()->getDistanceTable()))->getVolume();
    }

}