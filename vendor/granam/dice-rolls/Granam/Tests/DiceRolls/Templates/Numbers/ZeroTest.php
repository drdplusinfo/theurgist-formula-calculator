<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Numbers;

use Granam\DiceRolls\Templates\Numbers\Zero;
use Granam\Integer\IntegerInterface;
use PHPUnit\Framework\TestCase;

class ZeroTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $zero = Zero::getIt();
        self::assertSame(0, $zero->getValue());
        self::assertSame('0', "$zero");
        self::assertInstanceOf(IntegerInterface::class, $zero);
    }
}