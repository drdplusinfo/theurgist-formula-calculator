<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Numbers;

class Zero extends Number
{
    /**
     * @return Number|Zero
     */
    public static function getIt(): Zero
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return self::getInstance(0);
    }
}