<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\Roller;
use Granam\DiceRolls\Templates\Numbers\One;
use Granam\DiceRolls\Templates\Dices\Dice1d10;
use Granam\DiceRolls\Templates\Evaluators\OneToOneEvaluator;
use Granam\DiceRolls\Templates\RollOn\NoRollOn;

/**
 * Useful for D&D
 */
class Roller1d10 extends Roller
{
    private static $roller1d10;

    /**
     * @return Roller1d10
     */
    public static function getIt(): Roller1d10
    {
        if (self::$roller1d10 === null) {
            self::$roller1d10 = new static();
        }

        return self::$roller1d10;
    }

    public function __construct()
    {
        parent::__construct(
            Dice1d10::getIt(),
            One::getIt(),
            OneToOneEvaluator::getIt(),
            NoRollOn::getIt(),
            NoRollOn::getIt()
        );
    }
}