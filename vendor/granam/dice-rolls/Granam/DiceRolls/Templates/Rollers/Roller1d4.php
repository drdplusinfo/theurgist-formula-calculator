<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\Roller;
use Granam\DiceRolls\Templates\Numbers\One;
use Granam\DiceRolls\Templates\Dices\Dice1d4;
use Granam\DiceRolls\Templates\Evaluators\OneToOneEvaluator;
use Granam\DiceRolls\Templates\RollOn\NoRollOn;

/**
 * Useful for D&D
 */
class Roller1d4 extends Roller
{
    private static $roller1d4;

    /**
     * @return Roller1d4
     */
    public static function getIt(): Roller1d4
    {
        if (self::$roller1d4 === null) {
            self::$roller1d4 = new static();
        }

        return self::$roller1d4;
    }

    public function __construct()
    {
        $noRollOn = new NoRollOn();
        parent::__construct(
            new Dice1d4(),
            One::getIt(),
            OneToOneEvaluator::getIt(),
            $noRollOn,
            $noRollOn
        );
    }
}