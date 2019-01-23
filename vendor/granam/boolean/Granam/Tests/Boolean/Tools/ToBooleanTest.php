<?php
namespace Granam\Tests\Boolean;

use Granam\Boolean\Tools\ToBoolean;
use PHPUnit\Framework\TestCase;

class ToBooleanTest extends TestCase
{

    /**
     * @test
     */
    public function I_can_convert_value()
    {
        self::assertFalse(ToBoolean::toBoolean(false));
        self::assertTrue(ToBoolean::toBoolean(true));
        self::assertFalse(ToBoolean::toBoolean(''));
        self::assertTrue(ToBoolean::toBoolean(123456));

        self::assertTrue((bool)'0.0');
        self::assertTrue(ToBoolean::toBoolean('0.0'));

        self::assertTrue((bool)"\n");
        self::assertTrue(ToBoolean::toBoolean("\n"));
    }

    /**
     * @test
     */
    public function I_can_convert_any_number()
    {
        foreach ([0, 1.0, -2.36, 99.99, 8, -55] as $value) {
            self::assertSame((bool)$value, ToBoolean::toBoolean($value));
        }
    }

    /**
     * @test
     */
    public function I_can_use_zero_to_get_false()
    {
        $integerZero = 0;
        self::assertFalse(ToBoolean::toBoolean($integerZero));
        self::assertSame((bool)$integerZero, ToBoolean::toBoolean($integerZero));
        $floatZero = 0.0;
        self::assertFalse(ToBoolean::toBoolean($floatZero));
        self::assertSame((bool)$floatZero, ToBoolean::toBoolean($floatZero));
    }

    /**
     * @test
     */
    public function I_can_use_null_as_false_if_not_strict()
    {
        self::assertFalse(ToBoolean::toBoolean(null, false /* not strict */));
        self::assertSame((bool)null, ToBoolean::toBoolean(null, false /* not strict */));
    }

    /**
     * @test
     * @expectedException \Granam\Boolean\Tools\Exceptions\WrongParameterType
     */
    public function I_can_not_use_null_by_default()
    {
        ToBoolean::toBoolean(null);
    }

    /**
     * @test
     * @expectedException \Granam\Boolean\Tools\Exceptions\WrongParameterType
     */
    public function I_can_not_use_null_if_strict()
    {
        ToBoolean::toBoolean(null, true /* strict */);
    }

    /**
     * @test
     * @expectedException \Granam\Boolean\Tools\Exceptions\WrongParameterType
     */
    public function I_cannot_use_array()
    {
        ToBoolean::toBoolean([]);
    }

    /**
     * @test
     * @expectedException \Granam\Boolean\Tools\Exceptions\WrongParameterType
     */
    public function I_cannot_use_resource()
    {
        ToBoolean::toBoolean(tmpfile());
    }

    /**
     * @test
     * @expectedException \Granam\Boolean\Tools\Exceptions\WrongParameterType
     */
    public function I_cannot_use_object()
    {
        ToBoolean::toBoolean(new \stdClass());
    }

    /**
     * @test
     */
    public function I_can_use_to_string_object()
    {
        $toStringObject = new TestToBooleanToString($integerZero = 0);
        self::assertSame((bool)$integerZero, ToBoolean::toBoolean($toStringObject));
        $toStringObject = new TestToBooleanToString($integer = 12345);
        self::assertSame((bool)$integer, ToBoolean::toBoolean($toStringObject));
        $toStringObject = new TestToBooleanToString($stringInteger = '98765');
        self::assertSame((bool)$stringInteger, ToBoolean::toBoolean($toStringObject));
        $toStringObject = new TestToBooleanToString($float = 123.456);
        self::assertSame((bool)$float, ToBoolean::toBoolean($toStringObject));
        $toStringObject = new TestToBooleanToString($stringFloat = '987.654');
        self::assertSame((bool)$stringFloat, ToBoolean::toBoolean($toStringObject));
        $toStringObject = new TestToBooleanToString($emptyString = '');
        self::assertSame((bool)$emptyString, ToBoolean::toBoolean($toStringObject));
        $toStringObject = new TestToBooleanToString($someString = 'foo');
        self::assertSame((bool)$someString, ToBoolean::toBoolean($toStringObject));
    }
}

/** inner */
class TestToBooleanToString
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return (string)$this->value;
    }
}