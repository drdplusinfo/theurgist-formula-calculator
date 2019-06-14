<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonInvisibility;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameterTest;

class DemonInvisibilityTest extends PositiveCastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_invisibility_value()
    {
        $demonInvisibility = new DemonInvisibility([123, 0], Tables::getIt());
        self::assertSame(123, $demonInvisibility->getValue());
    }
}