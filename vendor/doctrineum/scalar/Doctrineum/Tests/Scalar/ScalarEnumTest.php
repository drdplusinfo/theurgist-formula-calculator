<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Tests\Scalar;

use Doctrineum\Tests\Scalar\Helpers\TestInheritedScalarEnum;
use Doctrineum\Tests\Scalar\Helpers\TestInvalidExistingScalarEnumUsage;
use Doctrineum\Tests\Scalar\Helpers\TestInvalidScalarEnumValue;
use Doctrineum\Tests\Scalar\Helpers\TestOfAbstractScalarEnum;
use Doctrineum\Tests\Scalar\Helpers\WithToStringTestObject;
use Granam\Tests\Tools\TestWithMockery;

class ScalarEnumTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_create_it(): void
    {
        $enumClass = $this->getEnumClass();
        $instance = $enumClass::getEnum('foo');
        self::assertInstanceOf($enumClass, $instance);
    }

    /**
     * @return string|\Doctrineum\Scalar\ScalarEnum
     */
    protected function getEnumClass(): string
    {
        return static::getSutClass();
    }

    /**
     * @test
     */
    public function I_got_same_instance_for_same_name(): void
    {
        $enumClass = $this->getEnumClass();
        $firstInstance = $enumClass::getEnum($firstValue = 'foo');
        $secondInstance = $enumClass::getEnum($secondValue = 'bar');
        $thirdInstance = $enumClass::getEnum($firstValue);
        self::assertNotSame(
            $firstInstance,
            $secondInstance,
            "Instance of enum $enumClass with value $firstValue should not be same as instance with value $secondValue"
        );
        self::assertSame($firstInstance, $thirdInstance);
    }

    /**
     * @test
     */
    public function I_got_same_value_as_I_created_with(): void
    {
        $enumClass = $this->getEnumClass();
        $enum = $enumClass::getEnum('foo');
        self::assertSame('foo', $enum->getValue());
    }

    /**
     * @test
     */
    public function I_got_same_value_as_string(): void
    {
        $enumClass = $this->getEnumClass();
        $enum = $enumClass::getEnum('foo');
        self::assertSame('foo', (string)$enum);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\CanNotBeCloned
     */
    public function I_can_not_clone_it(): void
    {
        $enumClass = $this->getEnumClass();
        $enum = $enumClass::getEnum('foo');
        /** @noinspection PhpExpressionResultUnusedInspection */
        clone $enum;
    }

    /**
     * @test
     */
    public function I_can_create_it_by_to_string_object_and_got_back_that_value(): void
    {
        $enumClass = $this->getEnumClass();
        $enum = $enumClass::getEnum(new WithToStringTestObject('foo'));
        self::assertSame('foo', $enum->getValue());
        self::assertSame('foo', (string)$enum);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     */
    public function I_can_not_create_it_by_object_without_to_string(): void
    {
        $enumClass = $this->getEnumClass();
        $enumClass::getEnum(new \stdClass());
    }

    /**
     * @test
     */
    public function I_can_compare_enums(): void
    {
        $sutClass = $this->getEnumClass();
        $firstEnum = $sutClass::getEnum('foo');
        self::assertTrue($firstEnum->is($firstEnum), 'Enum should recognize itself');

        $secondEnum = $sutClass::getEnum($secondValue = 'bar');
        self::assertFalse($firstEnum->is($secondEnum), 'Same classes with different values should not be equal');
        self::assertFalse($secondEnum->is($firstEnum), 'Same classes with different values should not be equal');

        $childEnum = TestInheritedScalarEnum::getEnum($secondValue);
        self::assertFalse($firstEnum->is($childEnum), 'Parent enum should not be equal to its child class');
        self::assertFalse($secondEnum->is($childEnum), 'Parent enum should not be equal to its child even if with same value');
        self::assertFalse($childEnum->is($secondEnum), 'Child enum should not be equal to its parent even if with same value');
    }

    /**
     * inner namespace test
     */

    /**
     * @test
     */
    public function inherited_enum_with_same_value_lives_in_own_inner_namespace(): void
    {
        $enumClass = $this->getEnumClass();

        $enum = $enumClass::getEnum($value = 'foo');
        self::assertInstanceOf($enumClass, $enum);
        self::assertSame($value, $enum->getValue());
        self::assertSame($value, (string)$enum);

        $inDifferentNamespace = $this->getInheritedEnum($value);
        self::assertInstanceOf($enumClass, $inDifferentNamespace);
        self::assertSame($enum->getValue(), $inDifferentNamespace->getValue());
        self::assertNotSame($enum, $inDifferentNamespace);
    }

    protected function getInheritedEnum($value)
    {
        return new TestInheritedScalarEnum($value);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\EnumIsAlreadyBuilt
     */
    public function adding_an_existing_enum_cause_exception(): void
    {
        TestInvalidExistingScalarEnumUsage::forceGetting(false);
        TestInvalidExistingScalarEnumUsage::forceAdding(true);
        // getting twice to internally add twice
        TestInvalidExistingScalarEnumUsage::getEnum('foo');
        TestInvalidExistingScalarEnumUsage::getEnum('foo');
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\EnumIsNotBuilt
     */
    public function getting_an_non_existing_enum_cause_exception(): void
    {
        TestInvalidExistingScalarEnumUsage::forceAdding(false);
        TestInvalidExistingScalarEnumUsage::forceGetting(true);
        TestInvalidExistingScalarEnumUsage::getEnum('bar');
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     */
    public function using_invalid_value_without_casting_cause_exception(): void
    {
        TestInvalidScalarEnumValue::getEnum(new \stdClass());
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     */
    public function I_can_not_create_it_with_null(): void
    {
        $sutClass = $this->getEnumClass();
        $sutClass::getEnum(null);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\CanNotCreateInstanceOfAbstractEnum
     * @expectedExceptionMessageRegExp ~abstract.+TestOfAbstractScalarEnum~
     */
    public function I_am_stopped_by_exception_if_trying_to_create_abstract_enum(): void
    {
        TestOfAbstractScalarEnum::getEnum('foo');
    }
}