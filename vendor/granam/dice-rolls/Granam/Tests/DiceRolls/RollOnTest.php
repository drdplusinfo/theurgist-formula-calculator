<?php declare(strict_types=1);

namespace Granam\Tests\DiceRolls;

use Granam\DiceRolls\RollOn;
use PHPUnit\Framework\TestCase;

class RollOnTest extends TestCase
{

    /**
     * @test
     */
    public function I_can_use_roll_on_interface()
    {
        self::assertTrue(interface_exists(RollOn::class));
        $reflection = new \ReflectionClass(RollOn::class);
        self::assertTrue($reflection->hasMethod('shouldHappen'));
        $shouldHappen = new \ReflectionMethod(RollOn::class, 'shouldHappen');
        $parameters = $shouldHappen->getParameters();
        self::assertCount(1, $parameters);
        $parameter = current($parameters);
        /** @var \ReflectionParameter $parameter */
        self::assertFalse($parameter->isOptional());
        self::assertSame('rolledValue', $parameter->getName());
        self::assertTrue($reflection->hasMethod('rollDices'));
    }
}