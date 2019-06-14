<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\BaseProperties\Knack;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonKnack;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameterTest;

class DemonKnackTest extends CastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_knack()
    {
        $demonKnack = new DemonKnack([123, 0], Tables::getIt());
        self::assertSame(Knack::getIt(123), $demonKnack->getKnack());
    }
}