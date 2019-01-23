<?php
declare(strict_types = 1);

namespace Granam\Tests\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\DiceRoll;
use Granam\Tests\Tools\TestWithMockery;

abstract class AbstractRollerTest extends TestWithMockery
{

	public const MAX_ROLL_ATTEMPTS = 10000;
	public const ROLLS_ROUNDS = 5;

	abstract public function I_can_create_it();

	abstract public function I_can_roll_by_it();

	protected function summarizeDiceRolls(array $diceRolls)
	{
		return \array_sum(
			\array_map(
				function (DiceRoll $diceRoll) {
					return $diceRoll->getValue();
				},
				$diceRolls
			)
		);
	}
}