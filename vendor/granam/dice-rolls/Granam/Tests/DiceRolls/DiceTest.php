<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls;

use Granam\DiceRolls\Dice;
use PHPUnit\Framework\TestCase;

class DiceTest extends TestCase
{

    /**
     * @test
     */
    public function I_can_use_dice_interface()
    {
        self::assertTrue(interface_exists(Dice::class));
        $reflection = new \ReflectionClass(Dice::class);
        $methods = $reflection->getMethods();
        self::assertCount(2, $methods);
        self::assertTrue($reflection->hasMethod('getMinimum'));
        $getMinimum = new \ReflectionMethod(Dice::class, 'getMinimum');
        self::assertSame(0, $getMinimum->getNumberOfParameters());
        self::assertTrue($reflection->hasMethod('getMaximum'));
        $getMaximum = new \ReflectionMethod(Dice::class, 'getMaximum');
        self::assertSame(0, $getMaximum->getNumberOfParameters());
    }
}