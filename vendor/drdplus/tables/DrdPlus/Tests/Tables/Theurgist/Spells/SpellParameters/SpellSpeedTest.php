<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameterTest;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;

class SpellSpeedTest extends CastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_speed()
    {
        $speed = new SpellSpeed(['35', '332211'], Tables::getIt());
        self::assertSame(35, $speed->getValue());
        self::assertEquals(
            new SpeedBonus(35, Tables::getIt()->getSpeedTable()),
            $speed->getSpeedBonus()
        );
    }
}