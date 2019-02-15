<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Wounds;

use DrdPlus\Tables\Measurements\Measurement;
use DrdPlus\Tables\Measurements\Wounds\Wounds;
use DrdPlus\Tables\Table;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfMeasurement;

class WoundsTest extends AbstractTestOfMeasurement
{

    protected function createSutWithTable(string $sutClass, int $amount, string $unit, Table $table): Measurement
    {
        return new $sutClass($amount, $table, $unit);
    }

    protected function getDefaultUnit(): string
    {
        return Wounds::WOUNDS;
    }
}