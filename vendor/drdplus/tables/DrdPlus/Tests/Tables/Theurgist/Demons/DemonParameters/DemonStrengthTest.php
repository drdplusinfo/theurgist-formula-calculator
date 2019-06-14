<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\BaseProperties\Strength;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonStrength;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameterTest;

class DemonStrengthTest extends CastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_strength()
    {
        $demonStrength = new DemonStrength([123, 0], Tables::getIt());
        self::assertSame(Strength::getIt(123), $demonStrength->getStrength());
    }
}