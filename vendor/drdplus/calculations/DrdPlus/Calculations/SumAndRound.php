<?php declare(strict_types=1);

namespace DrdPlus\Calculations;

use Granam\Float\Tools\ToFloat;
use Granam\Strict\Object\StrictObject;

class SumAndRound extends StrictObject
{

    /**
     * See PPH page 11 left column, @link https://pph.drdplus.jaroslavtyc.com/?mode=dev&hide=covered#zaokrouhlovani
     *
     * @param number $number
     * @return int
     */
    public static function round($number): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return (int)round(ToFloat::toFloat($number));
    }

    /**
     * @param number $number
     * @return int
     */
    public static function floor($number): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return (int)floor(ToFloat::toFloat($number));
    }

    /**
     * @param number $number
     * @return int
     */
    public static function ceil($number): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return (int)ceil(ToFloat::toFloat($number));
    }

    /**
     * @param number $firstNumber
     * @param number $secondNumber
     * @return int
     */
    public static function average($firstNumber, $secondNumber): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return self::round((ToFloat::toFloat($firstNumber) + ToFloat::toFloat($secondNumber)) / 2);
    }

    /**
     * @param number $number
     * @return int
     */
    public static function half($number): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return self::round(ToFloat::toFloat($number) / 2);
    }

    /**
     * @param number $number
     * @return int
     */
    public static function flooredHalf($number): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return self::floor(ToFloat::toFloat($number) / 2);
    }

    /**
     * @param number $number
     * @return int
     */
    public static function ceiledHalf($number): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return self::ceil(ToFloat::toFloat($number) / 2);
    }

    /**
     * @param number $number
     * @return int
     */
    public static function third($number): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return self::round(ToFloat::toFloat($number) / 3);
    }

    /**
     * @param number $number
     * @return int
     */
    public static function flooredThird($number): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return self::floor(ToFloat::toFloat($number) / 3);
    }

    /**
     * @param number $number
     * @return int
     */
    public static function ceiledThird($number): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return self::ceil(ToFloat::toFloat($number) / 3);
    }
}