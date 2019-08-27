<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Fatigue;

use DrdPlus\Tables\Measurements\Fatigue\Fatigue;
use DrdPlus\Tables\Measurements\Fatigue\FatigueTable;
use DrdPlus\Tables\Measurements\Measurement;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Table;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfMeasurement;

class FatigueTest extends AbstractTestOfMeasurement
{

    /**
     * @param string $sutClass
     * @param int $amount
     * @param string $unit
     * @param Table $table
     * @return Measurement
     */
    protected function createSutWithTable(string $sutClass, int $amount, string $unit, Table $table): Measurement
    {
        self::assertSame(Fatigue::FATIGUE, $unit);
        self::assertInstanceOf(FatigueTable::class, $table);

        /** @var $table FatigueTable */
        return new Fatigue($amount, $table);
    }

    /**
     * @test
     */
    public function I_can_create_it_with_time_object_as_value()
    {
        $fatigue = new Fatigue(new Time(123, Time::MINUTE, Tables::getIt()->getTimeTable()), Tables::getIt()->getFatigueTable());
        self::assertSame(123, $fatigue->getValue());
    }
}