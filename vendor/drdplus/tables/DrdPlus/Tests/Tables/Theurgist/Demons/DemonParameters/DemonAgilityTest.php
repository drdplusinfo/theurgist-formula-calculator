<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\BaseProperties\Agility;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonAgility;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameterTest;

class DemonAgilityTest extends CastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_agility()
    {
        $demonAgility = new DemonAgility([123, 0], Tables::getIt());
        self::assertSame(Agility::getIt(123), $demonAgility->getAgility());
    }
}