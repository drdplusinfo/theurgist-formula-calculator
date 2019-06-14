<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonArea;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameterTest;

class DemonAreaTest extends PositiveCastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_area()
    {
        $demonArea = new DemonArea([123, 0], Tables::getIt());
        self::assertSame(123, $demonArea->getValue());
        self::assertEquals(new DistanceBonus(123, Tables::getIt()->getDistanceTable()), $demonArea->getDistanceBonus());
    }
}