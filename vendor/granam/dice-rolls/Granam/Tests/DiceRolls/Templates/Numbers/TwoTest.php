<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Numbers;

use Granam\DiceRolls\Templates\Numbers\Two;
use Granam\Integer\IntegerInterface;
use PHPUnit\Framework\TestCase;

class TwoTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $two = Two::getIt();
        self::assertSame(2, $two->getValue());
        self::assertSame('2', "$two");
        self::assertInstanceOf(IntegerInterface::class, $two);
    }
}