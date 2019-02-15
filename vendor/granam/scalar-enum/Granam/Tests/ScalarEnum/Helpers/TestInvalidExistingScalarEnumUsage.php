<?php
declare(strict_types=1);

namespace Granam\Tests\ScalarEnum\Helpers;

use Granam\ScalarEnum\ScalarEnum;
use Granam\ScalarEnum\ScalarEnumInterface;

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
     * @return \Granam\ScalarEnum\ScalarEnumInterface|null
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