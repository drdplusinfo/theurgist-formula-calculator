<?php
declare(strict_types = 1);

namespace Granam\Tests\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\Evaluators\OneToOneEvaluator;
use Granam\DiceRolls\Templates\Rollers\Roller2d6DrdPlus;
use Granam\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use Granam\DiceRolls\Templates\RollOn\RollOn12;
use Granam\DiceRolls\Templates\RollOn\RollOn2;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\Exceptions\PositiveIntegerCanNotBeNegative;

class Roller2d6DrdPlusTest extends AbstractRollerTest
{

	/**
	 * @test
	 */
	public function I_can_create_it(): void
	{
		$roller2d6DrdPlus = Roller2d6DrdPlus::getIt();
		self::assertSame($roller2d6DrdPlus, Roller2d6DrdPlus::getIt());
		self::assertInstanceOf(Dice1d6::class, $roller2d6DrdPlus->getDice());
		self::assertInstanceOf(IntegerInterface::class, $roller2d6DrdPlus->getNumberOfStandardRolls());
		self::assertSame(2, $roller2d6DrdPlus->getNumberOfStandardRolls()->getValue());
		self::assertInstanceOf(OneToOneEvaluator::class, $roller2d6DrdPlus->getDiceRollEvaluator());
		self::assertInstanceOf(RollOn12::class, $roller2d6DrdPlus->getBonusRollOn());
		self::assertInstanceOf(RollOn2::class, $roller2d6DrdPlus->getMalusRollOn());
	}

	/**
	 * @test
	 */
	public function I_can_roll_by_it(): void
	{
		$roller2d6DrdPlus = Roller2d6DrdPlus::getIt();
		$previousRoll = null;
		$atLeastTwoBonusesHappened = false;
		$atLeastTwoMalusesHappened = false;
		for ($attempt = 1; $attempt < self::MAX_ROLL_ATTEMPTS; $attempt++) {
			$roll = $roller2d6DrdPlus->roll();
			self::assertNotSame($previousRoll, $roll);
			self::assertInstanceOf(Roll2d6DrdPlus::class, $roll);
			if (\count($roll->getBonusDiceRolls()) > 2) { // at least 2 positive bonus rolls (+ last negative bonus roll)
				$atLeastTwoBonusesHappened = true;
				self::assertGreaterThan($this->summarizeDiceRolls($roll->getStandardDiceRolls()), $roll->getValue());
				self::assertCount(0, $roll->getMalusDiceRolls());
			} elseif (\count($roll->getMalusDiceRolls()) > 2) { // at least 2 positive malus rolls (+ last negative malus roll)
				$atLeastTwoMalusesHappened = true;
				self::assertLessThan($this->summarizeDiceRolls($roll->getStandardDiceRolls()), $roll->getValue());
				self::assertCount(0, $roll->getBonusDiceRolls());
			}
			if ($atLeastTwoBonusesHappened && $atLeastTwoMalusesHappened) {
				break;
			}
			$previousRoll = $roll;
		}

		self::assertLessThan(self::MAX_ROLL_ATTEMPTS, $attempt, 'Expected at least two bonuses in a row and two maluses in a row');
		self::assertEquals(new Roller2d6DrdPlus(), $roller2d6DrdPlus, 'Roller has to be stateless');
	}

	/**
	 * @test
	 */
	public function I_can_let_generate_roll_history(): void
	{
		$roller2d6DrdPlus = Roller2d6DrdPlus::getIt();
		$expectedRollsValues = [];
		$expectedRollsValues[] = $this->createMalusRollRange(-2, 4);
		$expectedRollsValues[] = $this->createMalusRollRange(-1, 3);
		$expectedRollsValues[] = $this->createMalusRollRange(0, 2);
		$expectedRollsValues[] = $this->createMalusRollRange(1, 1);
		$expectedRollsValues[] = $this->createStandardRollRange(2, [1, 1]);
		$expectedRollsValues[] = $this->createStandardRollRange(3, [1, 2]);
		$expectedRollsValues[] = $this->createStandardRollRange(4, [1, 3]);
		$expectedRollsValues[] = $this->createStandardRollRange(5, [1, 4]);
		$expectedRollsValues[] = $this->createStandardRollRange(6, [1, 5]);
		$expectedRollsValues[] = $this->createStandardRollRange(7, [1, 6]);
		$expectedRollsValues[] = $this->createStandardRollRange(8, [2, 6]);
		$expectedRollsValues[] = $this->createStandardRollRange(9, [3, 6]);
		$expectedRollsValues[] = $this->createStandardRollRange(10, [4, 6]);
		$expectedRollsValues[] = $this->createStandardRollRange(11, [5, 6]);
		$expectedRollsValues[] = $this->createStandardRollRange(12, [6, 6]);
		$expectedRollsValues[] = $this->createBonusRollRange(13, 1);
		$expectedRollsValues[] = $this->createBonusRollRange(14, 2);
		foreach ($expectedRollsValues as $expectedRollValues) {
			[$value, $rollRange, $confirmingMalusRollCount, $confirmingBonusRollCount] = $expectedRollValues;
			for ($rollNumber = 1; $rollNumber < 30; $rollNumber++) {
				try {
					$roll = $roller2d6DrdPlus->generateRoll($value);
				} catch (PositiveIntegerCanNotBeNegative $exception) {
					self::fail("Can not get roll history for value $value: " . $exception->getMessage()
							   . ";\n" . $exception->getTraceAsString());
				}
				self::assertSame($value, $roll->getValue());
				$standardDiceRolls = $roll->getStandardDiceRolls();
				self::assertCount(2, $standardDiceRolls);
				[$firstDiceRoll, $secondDiceRoll] = $standardDiceRolls;
				[$rollMin, $rollMax] = $rollRange;
				self::assertGreaterThanOrEqual($rollMin, $firstDiceRoll->getRolledNumber()->getValue());
				self::assertLessThanOrEqual($rollMax, $firstDiceRoll->getRolledNumber()->getValue());
				self::assertGreaterThanOrEqual($rollMin, $secondDiceRoll->getRolledNumber()->getValue());
				self::assertLessThanOrEqual($rollMax, $secondDiceRoll->getRolledNumber()->getValue());
				$malusDiceRolls = $roll->getMalusDiceRolls();
				self::assertCount($confirmingMalusRollCount + ($value <= 2 ? 1 /* failing malus roll */ : 0), $malusDiceRolls);
				$bonusDiceRolls = $roll->getBonusDiceRolls();
				self::assertCount($confirmingBonusRollCount + ($value >= 12 ? 1 /* failing bonus roll */ : 0), $bonusDiceRolls);
			}
		}
	}

	private function createMalusRollRange(int $value, int $confirmingMalusRollCount): array
	{
		return $this->createRollRange($value, [1, 1], $confirmingMalusRollCount, 0);
	}

	private function createStandardRollRange(int $value, array $rollRange): array
	{
		return $this->createRollRange($value, $rollRange, 0, 0);
	}

	private function createRollRange(int $rollValue, array $rollRange, int $confirmingMalusRollCount, int $confirmingBonusRollCount): array
	{
		$rollRange[1] = $rollRange[1] ?? $rollRange[0];

		return [$rollValue, $rollRange, $confirmingMalusRollCount, $confirmingBonusRollCount];
	}

	private function createBonusRollRange(int $value, int $confirmingBonusRollCount): array
	{
		return $this->createRollRange($value, [6, 6], 0, $confirmingBonusRollCount);
	}
}