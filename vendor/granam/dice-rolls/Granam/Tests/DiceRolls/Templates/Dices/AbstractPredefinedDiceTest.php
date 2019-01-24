<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Dices;

use Granam\DiceRolls\Templates\Dices\CustomDice;
use Granam\DiceRolls\Templates\Dices\Dice1d6;
use PHPUnit\Framework\TestCase;

abstract class AbstractPredefinedDiceTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_create_it(): void
    {
        $diceClass = $this->getDiceClass();
        $reflection = new \ReflectionClass($diceClass);
        $staticContainer = $reflection->getProperty($this->getStaticContainerName());
        $staticContainer->setAccessible(true);
        // to test factory method coverage (code coverage in separate process is broken)
        $staticContainer->setValue($diceClass, null);
        $dice = $diceClass::getIt();
        self::assertSame($dice, $diceClass::getIt());
        self::assertEquals($dice, new $diceClass);
    }

    /**
     * @return string|CustomDice|Dice1d6 ...
     */
    private function getDiceClass()
    {
        return preg_replace('~(?:[\\\]Tests)?([\\\].+)Test$~', '$1', static::class);
    }

    private function getStaticContainerName(): string
    {
       self::assertGreaterThan(0, preg_match('~\\\(?<baseName>[^\\\]+)$~', $this->getDiceClass(), $matches));
        $diceBaseName = $matches['baseName'];

        return lcfirst($diceBaseName);
    }
}