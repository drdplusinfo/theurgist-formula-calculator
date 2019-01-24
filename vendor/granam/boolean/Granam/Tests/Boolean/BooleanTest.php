<?php
namespace Granam\Tests\Boolean;

use Granam\Boolean\Boolean;
use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase
{

    /**
     * @test
     */
    public function I_can_get_value()
    {
        $withFalse = new Boolean($false = false);
        self::assertFalse($withFalse->getValue());
        self::assertSame((string)$false, (string)$withFalse);
        self::assertSame('', (string)$withFalse);
        $withTrue = new Boolean($true = true);
        self::assertTrue($withTrue->getValue());
        self::assertSame((string)$true, (string)$withTrue);
        self::assertSame('1', (string)$withTrue);
        $withString = new Boolean($stringValue = '');
        self::assertSame((bool)$stringValue, $withString->getValue());
        self::assertSame($stringValue, (string)$withString);
        $withInteger = new Boolean($integerValue = 123456);
        self::assertSame('1', (string)$withInteger);
        self::assertTrue(true, $withInteger->getValue());
    }

    /**
     * @test
     */
    public function I_can_use_any_number()
    {
        foreach ([1.0, -2.36, 99.99, 8, -55] as $value) {
            $boolean = new Boolean($value);
            self::assertTrue($boolean->getValue());
            self::assertSame((bool)$value, $boolean->getValue());
        }
    }

    /**
     * @test
     */
    public function I_can_use_zero_to_get_false()
    {
        $boolean = new Boolean($integerZero = 0);
        self::assertFalse($boolean->getValue());
        self::assertSame((bool)$integerZero, $boolean->getValue());
        $boolean = new Boolean($floatZero = 0.0);
        self::assertFalse($boolean->getValue());
        self::assertSame((bool)$floatZero, $boolean->getValue());
    }

    /**
     * @test
     */
    public function I_can_use_null_as_false_if_not_strict()
    {
        $boolean = new Boolean(null, false /* not strict */);
        self::assertFalse($boolean->getValue());
        self::assertSame((bool)null, $boolean->getValue());
    }

    /**
     * @test
     * @expectedException \Granam\Boolean\Tools\Exceptions\WrongParameterType
     */
    public function I_can_not_use_null_by_default()
    {
        new Boolean(null);
    }

    /**
     * @test
     * @expectedException \Granam\Boolean\Tools\Exceptions\WrongParameterType
     */
    public function I_can_not_use_null_if_strict()
    {
        new Boolean(null, true /* strict */);
    }

    /**
     * @test
     * @expectedException \Granam\Boolean\Tools\Exceptions\WrongParameterType
     */
    public function I_cannot_use_array()
    {
        new Boolean([]);
    }

    /**
     * @test
     * @expectedException \Granam\Boolean\Tools\Exceptions\WrongParameterType
     */
    public function I_cannot_use_resource()
    {
        new Boolean(tmpfile());
    }

    /**
     * @test
     * @expectedException \Granam\Boolean\Tools\Exceptions\WrongParameterType
     */
    public function I_cannot_use_object()
    {
        new Boolean(new \stdClass());
    }

    /**
     * @test
     */
    public function I_can_use_to_string_object()
    {
        $boolean = new Boolean(new TestWithToString($integerZero = 0));
        self::assertSame((bool)$integerZero, $boolean->getValue());
        $boolean = new Boolean(new TestWithToString($integer = 12345));
        self::assertSame((bool)$integer, $boolean->getValue());
        $stringBoolean = new Boolean(new TestWithToString($stringInteger = '98765'));
        self::assertSame((bool)$stringInteger, $stringBoolean->getValue());
        $floatBoolean = new Boolean(new TestWithToString($float = 123.456));
        self::assertSame((bool)$float, $floatBoolean->getValue());
        $stringFloatBoolean = new Boolean(new TestWithToString($stringFloat = '987.654'));
        self::assertSame((bool)$stringFloat, $stringFloatBoolean->getValue());
        $stringFloatBoolean = new Boolean(new TestWithToString($emptyString = ''));
        self::assertSame((bool)$emptyString, $stringFloatBoolean->getValue());
        $stringFloatBoolean = new Boolean(new TestWithToString($someString = 'foo'));
        self::assertSame((bool)$someString, $stringFloatBoolean->getValue());
    }
}

/** inner */
class TestWithToString
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