<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Granam\Boolean\Tools;

use Granam\Scalar\Tools\ToScalar;

class ToBoolean
{
    /**
     * @param mixed $value
     * @param bool $strict = true NULL is not allowed by default
     * @return bool
     * @throws \Granam\Boolean\Tools\Exceptions\WrongParameterType
     */
    public static function toBoolean($value, bool $strict = true): bool
    {
        return (bool)self::convertToScalar($value, $strict);
    }

    /**
     * @param mixed $value
     * @param bool $strict
     * @return float|int|null|string
     * @throws \Granam\Boolean\Tools\Exceptions\WrongParameterType
     */
    private static function convertToScalar($value, bool $strict)
    {
        try {
            return ToScalar::toScalar($value, $strict);
        } catch (\Granam\Scalar\Tools\Exceptions\WrongParameterType $scalarException) {
            // wrapping by local one
            throw new Exceptions\WrongParameterType(
                $scalarException->getMessage(),
                $scalarException->getCode(),
                $scalarException
            );
        }
    }
}
