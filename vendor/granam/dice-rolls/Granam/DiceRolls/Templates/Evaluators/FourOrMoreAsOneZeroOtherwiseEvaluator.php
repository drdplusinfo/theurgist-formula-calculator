<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Evaluators;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\DiceRollEvaluator;
use Granam\DiceRolls\Templates\Numbers\One;
use Granam\DiceRolls\Templates\Numbers\Zero;
use Granam\Integer\IntegerInterface;
use Granam\Strict\Object\StrictObject;

class FourOrMoreAsOneZeroOtherwiseEvaluator extends StrictObject implements DiceRollEvaluator
{

    /** @var FourOrMoreAsOneZeroOtherwiseEvaluator|null */
    private static $fourOrMoreAsOneZeroOtherwiseEvaluator;

    /**
     * @return FourOrMoreAsOneZeroOtherwiseEvaluator
     */
    public static function getIt(): FourOrMoreAsOneZeroOtherwiseEvaluator
    {
        if (self::$fourOrMoreAsOneZeroOtherwiseEvaluator === null) {
            self::$fourOrMoreAsOneZeroOtherwiseEvaluator = new static();
        }

        return self::$fourOrMoreAsOneZeroOtherwiseEvaluator;
    }

    /**
     * @param DiceRoll $diceRoll
     * @return IntegerInterface
     */
    public function evaluateDiceRoll(DiceRoll $diceRoll): IntegerInterface
    {
        if ($diceRoll->getRolledNumber()->getValue() >= 4) {
            return One::getIt();
        }

        return Zero::getIt();
    }
}