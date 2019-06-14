<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Wounds;

use DrdPlus\Tables\Measurements\Measurement;
use DrdPlus\Tables\Measurements\Wounds\Wounds;
use DrdPlus\Tables\Measurements\Wounds\WoundsTable;
use DrdPlus\Tables\Table;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfMeasurement;

class WoundsTest extends AbstractTestOfMeasurement
{

    protected function createSutWithTable(string $sutClass, int $amount, string $unit, Table $table): Measurement
    {
        /** @var WoundsTable $table */
        return new Wounds($amount, $table);
    }

    protected function getDefaultUnit(): string
    {
        return Wounds::WOUNDS;
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Wounds\Exceptions\WoundsCanNotBeNegative
     * @expectedExceptionMessageRegExp ~-1~
     */
    public function I_can_not_create_negative_wounds()
    {
        new Wounds(-1, Tables::getIt()->getWoundsTable());
    }
}