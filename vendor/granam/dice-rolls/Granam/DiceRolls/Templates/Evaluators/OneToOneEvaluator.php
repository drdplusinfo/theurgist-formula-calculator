<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Evaluators;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\DiceRollEvaluator;
use Granam\Integer\IntegerInterface;
use Granam\Strict\Object\StrictObject;

class OneToOneEvaluator extends StrictObject implements DiceRollEvaluator
{

    /** @var OneToOneEvaluator|null */
    private static $oneToOneEvaluator;

    /**
     * @return OneToOneEvaluator
     */
    public static function getIt(): OneToOneEvaluator
    {
        if (self::$oneToOneEvaluator === null) {
            self::$oneToOneEvaluator = new static();
        }

        return self::$oneToOneEvaluator;
    }

    /**
     * @param DiceRoll $diceRoll
     * @return IntegerInterface
     */
    public function evaluateDiceRoll(DiceRoll $diceRoll): IntegerInterface
    {
        return $diceRoll->getRolledNumber();
    }
}