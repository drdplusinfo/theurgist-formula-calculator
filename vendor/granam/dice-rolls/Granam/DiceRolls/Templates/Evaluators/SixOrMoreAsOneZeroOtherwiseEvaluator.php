<?php declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Evaluators;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\DiceRollEvaluator;
use Granam\DiceRolls\Templates\Numbers\One;
use Granam\DiceRolls\Templates\Numbers\Zero;
use Granam\Integer\IntegerInterface;
use Granam\Strict\Object\StrictObject;

class SixOrMoreAsOneZeroOtherwiseEvaluator extends StrictObject implements DiceRollEvaluator
{
    /**
     * @var SixOrMoreAsOneZeroOtherwiseEvaluator|null
     */
    private static $sixOrMoreAsOneZeroOtherwiseEvaluator;

    /**
     * @return SixOrMoreAsOneZeroOtherwiseEvaluator
     */
    public static function getIt(): SixOrMoreAsOneZeroOtherwiseEvaluator
    {
        if (self::$sixOrMoreAsOneZeroOtherwiseEvaluator === null) {
            self::$sixOrMoreAsOneZeroOtherwiseEvaluator = new static();
        }

        return self::$sixOrMoreAsOneZeroOtherwiseEvaluator;
    }

    /**
     * @param DiceRoll $diceRoll
     * @return \Granam\DiceRolls\Templates\Numbers\Number|IntegerInterface
     */
    public function evaluateDiceRoll(DiceRoll $diceRoll): IntegerInterface
    {
        return $diceRoll->getRolledNumber()->getValue() >= 6
            ? One::getIt()
            : Zero::getIt();
    }
}