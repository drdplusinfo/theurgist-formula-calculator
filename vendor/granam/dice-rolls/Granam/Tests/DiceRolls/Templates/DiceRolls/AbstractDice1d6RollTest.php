<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\DiceRolls;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\DiceRollEvaluator;
use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\Integer\IntegerInterface;
use Granam\Integer\PositiveIntegerObject;
use Granam\Tests\Tools\TestWithMockery;

abstract class AbstractDice1d6RollTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_use_it(): void
    {
        $sutClass = self::getSutClass();
        /** @var DiceRoll $diceRoll */
        $diceRoll = new $sutClass($rolledNumber = $this->createRolledNumber($rolledValue = \random_int(1, 6)), 1);
        self::assertSame(Dice1d6::getIt(), $diceRoll->getDice());
        self::assertSame($rolledNumber->getValue(), $diceRoll->getRolledNumber()->getValue());
        self::assertEquals(new PositiveIntegerObject(1), $diceRoll->getSequenceNumber());
        self::assertSame($this->getDiceRollEvaluator(), $diceRoll->getDiceRollEvaluator());
        self::assertSame($this->getDiceRollEvaluator()->evaluateDiceRoll($diceRoll)->getValue(), $diceRoll->getValue());
        self::assertSame((string)$rolledValue, (string)$diceRoll->getRolledNumber());
    }

    abstract protected function getDiceRollEvaluator(): DiceRollEvaluator;

    /**
     * @param int $rolledValue
     * @return \Mockery\MockInterface|IntegerInterface
     */
    private function createRolledNumber($rolledValue)
    {
        $rolledNumber = $this->mockery(IntegerInterface::class);
        $rolledNumber->shouldReceive('getValue')
            ->andReturn($rolledValue);

        return $rolledNumber;
    }

    /**
     * @test
     * @expectedException \Granam\DiceRolls\Templates\DiceRolls\Exceptions\Invalid1d6DiceRollValue
     * @expectedExceptionMessageRegExp ~got 0~
     */
    public function I_can_not_create_it_with_zero_or_less(): void
    {
        $sutClass = self::getSutClass();
        new $sutClass($rolledNumber = $this->createRolledNumber(0), 1);
    }

    /**
     * @test
     * @expectedException \Granam\DiceRolls\Templates\DiceRolls\Exceptions\Invalid1d6DiceRollValue
     * @expectedExceptionMessageRegExp ~got 7~
     */
    public function I_can_not_create_it_with_greater_number_than_six(): void
    {
        $sutClass = self::getSutClass();
        new $sutClass($rolledNumber = $this->createRolledNumber(7), 1);
    }

    /**
     * @test
     * @expectedException \Granam\DiceRolls\Exceptions\InvalidSequenceNumber
     * @expectedExceptionMessageRegExp ~got 0~
     */
    public function I_can_not_create_it_with_zero_or_less_sequence_number(): void
    {
        $sutClass = self::getSutClass();
        new $sutClass($rolledNumber = $this->createRolledNumber(5), 0);
    }
}