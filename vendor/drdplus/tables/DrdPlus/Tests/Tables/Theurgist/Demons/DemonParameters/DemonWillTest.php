<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\BaseProperties\Will;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonWill;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameterTest;

class DemonWillTest extends CastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_will()
    {
        $demonWill = new DemonWill([123, 0], Tables::getIt());
        $expectedWill = Will::getIt(123);
        $currentWill = $demonWill->getWill();
        self::assertEquals($expectedWill, $currentWill);
        self::assertSame($expectedWill, $currentWill);
    }
}