<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Evaluators;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\DiceRollEvaluator;
use Granam\DiceRolls\Templates\Numbers\MinusOne;
use Granam\DiceRolls\Templates\Numbers\Zero;
use Granam\Integer\IntegerInterface;
use Granam\Strict\Object\StrictObject;

class ThreeOrLessAsMinusOneZeroOtherwiseEvaluator extends StrictObject implements DiceRollEvaluator
{
    /**
     * @var ThreeOrLessAsMinusOneZeroOtherwiseEvaluator|null
     */
    private static $threeOrLessAsMinusOneZeroOtherwiseEvaluator;

    /**
     * @return ThreeOrLessAsMinusOneZeroOtherwiseEvaluator
     */
    public static function getIt(): ThreeOrLessAsMinusOneZeroOtherwiseEvaluator
    {
        if (self::$threeOrLessAsMinusOneZeroOtherwiseEvaluator === null) {
            self::$threeOrLessAsMinusOneZeroOtherwiseEvaluator = new static();
        }

        return self::$threeOrLessAsMinusOneZeroOtherwiseEvaluator;
    }

    /**
     * @param DiceRoll $diceRoll
     * @return \Granam\DiceRolls\Templates\Numbers\Number|IntegerInterface
     */
    public function evaluateDiceRoll(DiceRoll $diceRoll): IntegerInterface
    {
        return $diceRoll->getRolledNumber()->getValue() <= 3
            ? MinusOne::getIt()
            : Zero::getIt();
    }
}