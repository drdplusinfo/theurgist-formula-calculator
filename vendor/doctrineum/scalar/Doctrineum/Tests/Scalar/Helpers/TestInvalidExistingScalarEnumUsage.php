<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Tests\Scalar\Helpers;

use Doctrineum\Scalar\ScalarEnum;
use Doctrineum\Scalar\ScalarEnumInterface;

class TestInvalidExistingScalarEnumUsage extends ScalarEnum
{
    private static $forceAdding = false;
    private static $forceGetting = false;

    public static function forceAdding($force = true): void
    {
        self::$forceAdding = $force;
    }

    public static function forceGetting($force = true): void
    {
        self::$forceGetting = $force;
    }

    /**
     * @param float|int|string $enumValue
     * @param string $namespace
     * @return \Doctrineum\Scalar\ScalarEnumInterface|null
     */
    protected static function getEnumFromNamespace($enumValue, string $namespace): ?ScalarEnumInterface
    {
        $finalValue = static::convertToEnumFinalValue($enumValue);
        if (self::$forceAdding) {
            static::addCreatedEnum(static::createEnum($finalValue), $namespace);
        }

        if (self::$forceGetting) {
            return static::getCreatedEnum($finalValue, $namespace);
        }

        return null;
    }

}