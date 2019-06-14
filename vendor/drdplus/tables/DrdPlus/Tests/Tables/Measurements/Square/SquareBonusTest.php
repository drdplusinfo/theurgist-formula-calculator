<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Square;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Measurements\Square\SquareBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfBonus;

class SquareBonusTest extends AbstractTestOfBonus
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
        $distanceTable = Tables::getIt()->getDistanceTable();

        $squareMeterToKilometer = 1000 ** 2;
        $squareBonus = new SquareBonus(-40, $distanceTable);
        $square = $squareBonus->getSquare();
        self::assertSame(0.01, $square->getSquareMeters(), "Expected 0.01 as square in meters from bonus {$squareBonus}");
        self::assertSame(0.01 / $squareMeterToKilometer, $square->getSquareKilometers());
        self::assertSame($squareBonus->getValue(), $square->getBonus()->getValue());

        $squareBonus = new SquareBonus(0, $distanceTable);
        $square = $squareBonus->getSquare();
        self::assertSame(1.0, $square->getSquareMeters());
        self::assertSame(1.0 / $squareMeterToKilometer, $square->getSquareKilometers());
        self::assertSame($squareBonus->getValue(), $square->getBonus()->getValue());

        $distanceBonus = new DistanceBonus(119, $distanceTable);
        $distanceInMeters = $distanceBonus->getDistance(DistanceUnitCode::METER);
        $squareBonus = new SquareBonus($distanceBonus->getValue() * 2, $distanceTable);
        $square = $squareBonus->getSquare();
        self::assertSame(
            $distanceInMeters->getValue() ** 2,
            $square->getSquareMeters(),
            "Expected {$distanceInMeters->getValue()}^2= " . ($distanceInMeters->getValue() ** 2)
            . " as square in meters, got {$square->getSquareMeters()}"
        );
        $distanceInKilometers = $distanceBonus->getDistance(DistanceUnitCode::KILOMETER);
        self::assertSame(
            $distanceInKilometers->getValue() ** 2,
            $square->getSquareKilometers(),
            "Expected {$distanceInKilometers->getValue()}^2= " . ($distanceInKilometers->getValue() ** 2)
            . " as square in meters, got {$square->getSquareKilometers()}"
        );

        self::assertSame($squareBonus->getValue(), $square->getBonus()->getValue());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     */
    public function I_can_not_use_too_low_bonus_to_value(): void
    {
        (new SquareBonus(-40 /* minimal distance bonus */ * 2 /* doubled */ - 1 /* out of range */, Tables::getIt()->getDistanceTable()))->getSquare();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     */
    public function I_can_not_convert_too_high_bonus_into_too_detailed_unit(): void
    {
        (new SquareBonus(120 /* maximal distance bonus */ * 2 /* doubled */ + 1 /* out of range */, Tables::getIt()->getDistanceTable()))->getSquare();
    }
}