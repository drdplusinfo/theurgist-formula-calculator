<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonQuality;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameterTest;

class DemonQualityTest extends PositiveCastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_quality_value()
    {
        $demonQuality = new DemonQuality([123, 0], Tables::getIt());
        self::assertSame(123, $demonQuality->getValue());
    }
}