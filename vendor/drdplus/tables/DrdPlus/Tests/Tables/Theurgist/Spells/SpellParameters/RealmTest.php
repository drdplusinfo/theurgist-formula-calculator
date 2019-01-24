<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\IntegerAddAndSubTestTrait;
use Granam\Integer\IntegerInterface;
use Granam\Tests\Tools\TestWithMockery;

class RealmTest extends TestWithMockery
{
    use IntegerAddAndSubTestTrait;

    /**
     * @test
     */
    public function I_can_use_it()
    {
        $sutClass = self::getSutClass();

        /** @var IntegerInterface $zero */
        $zero = new $sutClass(0);
        self::assertSame(0, $zero->getValue());
        self::assertSame('0', (string)$zero);

        $positive = new $sutClass(123);
        /** @var IntegerInterface $positive */
        self::assertSame(123, $positive->getValue());
        self::assertSame('123', (string)$positive);
    }

    /**
     * @test
     * @expectedException \Granam\Integer\Tools\Exceptions\PositiveIntegerCanNotBeNegative
     * @expectedExceptionMessageRegExp ~-456~
     */
    public function I_can_not_create_it_negative()
    {
        $sutClass = self::getSutClass();
        new $sutClass(-456);
    }

}