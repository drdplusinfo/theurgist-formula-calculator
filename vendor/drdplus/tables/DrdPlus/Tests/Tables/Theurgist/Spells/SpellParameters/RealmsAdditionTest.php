<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAddition;
use PHPUnit\Framework\TestCase;

class RealmsAdditionTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $realmsAddition = new RealmsAddition('123');
        self::assertSame(123, $realmsAddition->getValue());
    }
}
