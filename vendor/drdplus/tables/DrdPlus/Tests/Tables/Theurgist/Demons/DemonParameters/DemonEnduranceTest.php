<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonEndurance;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameterTest;

class DemonEnduranceTest extends PositiveCastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_endurance()
    {
        $demonEndurance = new DemonEndurance([123, 0], Tables::getIt());
        self::assertSame(123, $demonEndurance->getValue());
    }
}