<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\IntegerAddAndSubTestTrait;
use Granam\Tests\Tools\TestWithMockery;

class RealmTest extends TestWithMockery
{
    use IntegerAddAndSubTestTrait;

    /**
     * @test
     */
    public function I_can_use_it()
    {
        $zero = new Realm(0);
        self::assertSame(0, $zero->getValue());
        self::assertSame('0', (string)$zero);

        $positive = new Realm(20);
        self::assertSame(20, $positive->getValue());
        self::assertSame('20', (string)$positive);
    }

    /**
     * @test
     */
    public function I_can_not_create_it_negative()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\RealmCanNotBeNegative::class);
        $this->expectExceptionMessageRegExp('~-456~');
        new Realm(-456);
    }
}