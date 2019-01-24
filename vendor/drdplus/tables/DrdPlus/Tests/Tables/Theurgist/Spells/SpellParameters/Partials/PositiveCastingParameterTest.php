<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials;

abstract class PositiveCastingParameterTest extends CastingParameterTest
{
    protected function I_can_create_it_negative()
    {
        self::assertFalse(false, 'Positive casting parameter can not be negative');
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\InvalidValueForPositiveCastingParameter
     * @expectedExceptionMessageRegExp ~infinite~
     */
    public function I_can_not_create_it_non_numeric()
    {
        $sutClass = self::getSutClass();
        new $sutClass(['infinite', '332211']);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\InvalidValueForPositiveCastingParameter
     * @expectedExceptionMessageRegExp ~-5~
     */
    public function I_can_not_create_it_negative()
    {
        $sutClass = self::getSutClass();
        new $sutClass(['-5']);
    }
}