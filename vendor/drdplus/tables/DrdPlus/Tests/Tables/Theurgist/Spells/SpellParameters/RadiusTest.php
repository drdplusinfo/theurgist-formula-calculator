<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameterTest;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Radius;

class RadiusTest extends CastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_distance()
    {
        $radius = new Radius(['63', '332211']);
        self::assertSame(63, $radius->getValue());
        self::assertEquals(
            (new DistanceBonus(63, $distanceTable = new DistanceTable()))->getDistance(),
            $radius->getDistance($distanceTable)
        );
    }
}