<?php declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials;

use DrdPlus\Tables\Tables;

abstract class PositiveCastingParameterTest extends CastingParameterTest
{
    protected function I_can_create_it_negative()
    {
        self::assertFalse(false, 'Positive casting parameter can not be negative');
    }

    /**
     * @test
     */
    public function I_can_not_create_it_non_numeric()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\InvalidValueForPositiveCastingParameter::class);
        $sutClass = self::getSutClass();
        new $sutClass(['infinite', '332211'], Tables::getIt());
    }

    /**
     * @test
     */
    public function I_can_not_create_it_negative()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\InvalidValueForPositiveCastingParameter::class);
        $this->expectExceptionMessageRegExp('~-5~');
        $sutClass = self::getSutClass();
        new $sutClass(['-5'], Tables::getIt());
    }
}