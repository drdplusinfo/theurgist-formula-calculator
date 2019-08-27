<?php declare(strict_types=1);

declare(strict_types = 1);

namespace Granam\DiceRolls\Templates\DiceRolls;

use Granam\DiceRolls\Templates\Evaluators\ThreeOrLessAsMinusOneZeroOtherwiseEvaluator;
use Granam\Integer\IntegerInterface;

class Dice1d6DrdPlusMalusRoll extends AbstractDice1d6Roll
{

	/**
	 * @param IntegerInterface|int $rolledNumber
	 * @param IntegerInterface|int $sequenceNumber
	 */
	public function __construct($rolledNumber, $sequenceNumber)
	{
		parent::__construct(
			$rolledNumber,
			ThreeOrLessAsMinusOneZeroOtherwiseEvaluator::getIt(),
			$sequenceNumber
		);
	}
}