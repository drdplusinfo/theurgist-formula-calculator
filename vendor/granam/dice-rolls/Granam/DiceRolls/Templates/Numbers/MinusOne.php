<?php declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Numbers;

class MinusOne extends Number
{
    /**
     * @return Number|MinusOne
     */
    public static function getIt(): MinusOne
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return self::getInstance(-1);
    }
}