<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonCapacity;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameterTest;

class DemonCapacityTest extends PositiveCastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_capacity()
    {
        $demonCapacity = new DemonCapacity([123, 0], Tables::getIt());
        self::assertSame(123, $demonCapacity->getValue());
    }
}