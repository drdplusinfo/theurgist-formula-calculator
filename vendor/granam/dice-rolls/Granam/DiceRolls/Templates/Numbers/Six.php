<?php declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Numbers;

class Six extends Number
{
    /**
     * @return Number|Six
     */
    public static function getIt(): Six
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return self::getInstance(6);
    }
}