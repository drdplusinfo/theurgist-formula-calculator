<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonArmor;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameterTest;

class DemonArmorTest extends PositiveCastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_armor()
    {
        $demonArmor = new DemonArmor([123, 0], Tables::getIt());
        self::assertSame(123, $demonArmor->getValue());
    }
}