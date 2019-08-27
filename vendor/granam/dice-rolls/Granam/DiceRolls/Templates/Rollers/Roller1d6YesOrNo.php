<?php declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\Roller;
use Granam\DiceRolls\Templates\Evaluators\FourOrMoreAsOneZeroOtherwiseEvaluator;
use Granam\DiceRolls\Templates\Numbers\One;
use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\RollOn\NoRollOn;

class Roller1d6YesOrNo extends Roller
{
    private static $roller1d6YesOrNo;

    /**
     * @return Roller1d6YesOrNo
     */
    public static function getIt(): Roller1d6YesOrNo
    {
        if (self::$roller1d6YesOrNo === null) {
            self::$roller1d6YesOrNo = new static();
        }

        return self::$roller1d6YesOrNo;
    }

    public function __construct()
    {
        parent::__construct(
            Dice1d6::getIt(), // roll with 1d6
            One::getIt(), // once
            FourOrMoreAsOneZeroOtherwiseEvaluator::getIt(), // roll will result into zero or one equally
            NoRollOn::getIt(), // no re roll on high number
            NoRollOn::getIt() // no re roll on low number
        );
    }
}