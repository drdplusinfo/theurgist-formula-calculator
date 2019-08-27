<?php declare(strict_types=1);

namespace Granam\Tests\ScalarEnum\Helpers;

use Granam\ScalarEnum\ScalarEnum;

class TestInvalidScalarEnumValue extends ScalarEnum
{

    protected static function convertToEnumFinalValue($value)
    {
        // intentionally no conversion at all
        return $value;
    }
}