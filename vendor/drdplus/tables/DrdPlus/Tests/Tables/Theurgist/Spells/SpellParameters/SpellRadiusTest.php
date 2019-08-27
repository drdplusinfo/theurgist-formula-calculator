<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameterTest;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellRadius;

class SpellRadiusTest extends CastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_distance()
    {
        $radius = new SpellRadius(['63', '332211'], Tables::getIt());
        self::assertSame(63, $radius->getValue());
        self::assertEquals(DistanceBonus::getIt(63, Tables::getIt()), $radius->getDistanceBonus());
    }
}