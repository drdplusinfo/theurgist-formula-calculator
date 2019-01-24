<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\Roll;
use Granam\Integer\IntegerInterface;
use Granam\Integer\PositiveInteger;
use Granam\Tests\Tools\TestWithMockery;

class RollTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_create_it_without_bonus_and_malus_rolls()
    {
        $roll = new Roll($diceRolls = $this->createDiceRolls($values = [1, 2, 3]));
        self::assertInstanceOf(IntegerInterface::class, $roll);
        $rolledNumbers = $roll->getRolledNumbers();
        self::assertCount(\count($values), $rolledNumbers);
        foreach ($rolledNumbers as $index => $rolledNumber) {
            self::assertInstanceOf(IntegerInterface::class, $rolledNumber);
            self::assertSame($values[$index], $rolledNumber->getValue());
        }
        self::assertSame(array_sum($values), $roll->getValue());
        self::assertSame((string)array_sum($values), (string)$roll);
        self::assertSame($diceRolls, $roll->getDiceRolls());
        self::assertEquals($diceRolls, $roll->getStandardDiceRolls());
        self::assertEquals([], $roll->getBonusDiceRolls());
        self::assertEquals([], $roll->getMalusDiceRolls());
    }

    private function createDiceRolls(array $values)
    {
        $diceRolls = [];
        foreach ($values as $value) {
            $diceRoll = $this->mockery(DiceRoll::class);
            $diceRoll->shouldReceive('getRolledNumber')
                ->andReturn($rolledNumber = $this->mockery(PositiveInteger::class));
            $rolledNumber->shouldReceive('getValue')
                ->andReturn($value);
            $diceRoll->shouldReceive('getValue')
                ->andReturn($value); // assuming 1 to 1 evaluator
            $diceRolls[] = $diceRoll;
        }

        return $diceRolls;
    }

    /**
     * @test
     */
    public function I_can_create_it_with_bonus_but_without_malus_rolls()
    {
        $roll = new Roll(
            $standardDiceRolls = $this->createDiceRolls($standardValues = [1, 2, 3]),
            $bonusDiceRolls = $this->createDiceRolls($bonusValues = [11, 13, 17, 19])
        );
        $rolledNumbers = $roll->getRolledNumbers();
        $values = array_merge($standardValues, $bonusValues);
        self::assertCount(\count($values), $rolledNumbers);
        foreach ($rolledNumbers as $index => $rolledNumber) {
            self::assertInstanceOf(IntegerInterface::class, $rolledNumber);
            self::assertSame($values[$index], $rolledNumber->getValue());
        }
        self::assertSame(array_sum($values), $roll->getValue());
        self::assertSame((string)array_sum($values), (string)$roll);
        self::assertEquals(array_merge($standardDiceRolls, $bonusDiceRolls), $roll->getDiceRolls());
        self::assertEquals($standardDiceRolls, $roll->getStandardDiceRolls());
        self::assertEquals($bonusDiceRolls, $roll->getBonusDiceRolls());
        self::assertEquals([], $roll->getMalusDiceRolls());
    }

    /**
     * @test
     */
    public function I_can_create_it_without_bonus_but_with_malus_rolls()
    {
        $roll = new Roll(
            $standardDiceRolls = $this->createDiceRolls($standardValues = [1, 2, 3]),
            [],
            $malusDiceRolls = $this->createDiceRolls($malusValues = [5, 11])
        );
        $rolledNumbers = $roll->getRolledNumbers();
        $values = array_merge($standardValues, $malusValues);
        self::assertCount(\count($values), $rolledNumbers);
        foreach ($rolledNumbers as $index => $rolledNumber) {
            self::assertInstanceOf(IntegerInterface::class, $rolledNumber);
            self::assertSame($values[$index], $rolledNumber->getValue());
        }
        self::assertSame(array_sum($values), $roll->getValue());
        self::assertSame((string)array_sum($values), (string)$roll);
        self::assertEquals(array_merge($standardDiceRolls, $malusDiceRolls), $roll->getDiceRolls());
        self::assertEquals($standardDiceRolls, $roll->getStandardDiceRolls());
        self::assertEquals([], $roll->getBonusDiceRolls());
        self::assertEquals($malusDiceRolls, $roll->getMalusDiceRolls());
    }

    /**
     * @test
     */
    public function I_can_create_it_with_bonus_and_malus_rolls()
    {
        $roll = new Roll(
            $standardDiceRolls = $this->createDiceRolls($standardValues = [1, 2, 3]),
            $bonusDiceRolls = $this->createDiceRolls($bonusValues = [11, 13, 17, 19]),
            $malusDiceRolls = $this->createDiceRolls($malusValues = [5, 6])
        );
        $rolledNumbers = $roll->getRolledNumbers();
        $values = array_merge($standardValues, $bonusValues, $malusValues);
        self::assertCount(\count($values), $rolledNumbers);
        foreach ($rolledNumbers as $index => $rolledNumber) {
            self::assertInstanceOf(IntegerInterface::class, $rolledNumber);
            self::assertSame($values[$index], $rolledNumber->getValue());
        }
        self::assertSame(array_sum($values), $roll->getValue());
        self::assertSame((string)array_sum($values), (string)$roll);
        self::assertEquals(array_merge($standardDiceRolls, $bonusDiceRolls, $malusDiceRolls), $roll->getDiceRolls());
        self::assertEquals($standardDiceRolls, $roll->getStandardDiceRolls());
        self::assertEquals($bonusDiceRolls, $roll->getBonusDiceRolls());
        self::assertEquals($malusDiceRolls, $roll->getMalusDiceRolls());
    }

    /**
     * @test
     */
    public function I_can_create_empty_roll()
    {
        foreach ([new Roll([]), new Roll([], []), new Roll([], [], [])] as $roll) {
            /** @var Roll $roll */
            self::assertEquals([], $roll->getStandardDiceRolls());
            self::assertEquals([], $roll->getMalusDiceRolls());
            self::assertEquals([], $roll->getBonusDiceRolls());
            self::assertEquals([], $roll->getDiceRolls());
            self::assertEquals([], $roll->getRolledNumbers());
            self::assertSame(0, $roll->getValue());
            self::assertSame('0', (string)$roll->getValue());
        }
    }
}