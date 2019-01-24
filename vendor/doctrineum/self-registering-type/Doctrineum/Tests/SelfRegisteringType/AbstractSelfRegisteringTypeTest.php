<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Tests\SelfRegisteringType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrineum\SelfRegisteringType\AbstractSelfRegisteringType;
use Granam\Tests\Tools\TestWithMockery;

abstract class AbstractSelfRegisteringTypeTest extends TestWithMockery
{

    /**
     * @test
     * @throws \ReflectionException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_register_it(): void
    {
        $typeClass = $this->getTypeClass();
        /** @var Type $instance */
        $instance = (new \ReflectionClass($typeClass))->newInstanceWithoutConstructor();
        self::assertSame($this->getExpectedTypeName(), $instance->getName(), 'Expected different name of the type');
        $typeClass::registerSelf();
        self::assertTrue(
            Type::hasType($this->getExpectedTypeName()),
            "After self-registration the type {$typeClass} has not been found by name {$this->getExpectedTypeName()}"
        );
    }

    /**
     * @return AbstractSelfRegisteringType|string
     */
    protected function getTypeClass(): string
    {
        $typeClass = preg_replace('~[\\\]Tests([\\\].+)Test$~', '$1', $testClass = static::class);
        self::assertTrue(
            class_exists($typeClass),
            "Expected type class {$typeClass} not found"
        );

        return $typeClass;
    }

    /**
     * @param string|null $typeClass
     * @return string
     */
    protected function getExpectedTypeName(string $typeClass = null): string
    {
        // like Doctrineum\Scalar\EnumType = EnumType
        $baseClassName = preg_replace('~(\w+\\\){0,6}(\w+)~', '$2', $typeClass ?: $this->getTypeClass());
        // like EnumType = Enum
        $baseTypeName = preg_replace('~Type$~', '', $baseClassName);

        // like FooBarEnum = Foo_Bar_Enum = foo_bar_enum
        return strtolower(preg_replace('~(\w)([A-Z])~', '$1_$2', $baseTypeName));
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_get_instance()
    {
        $typeClass = $this->getTypeClass();
        $instance = $this->createSut();
        self::assertInstanceOf($typeClass, $instance);

        return $instance;
    }

    /**
     * @return AbstractSelfRegisteringType|Type
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function createSut(): AbstractSelfRegisteringType
    {
        $typeClass = $this->getTypeClass();
        $typeClass::registerSelf();

        return Type::getType($this->getExpectedTypeName());
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_get_expected_type_name(): void
    {
        $typeClass = $this->getTypeClass();
        $typeName = $this->getExpectedTypeName();
        // like SELF_TYPED_ENUM
        $constantName = \strtoupper($typeName);
        self::assertTrue(\defined("$typeClass::$constantName"), "Expected constant with type name {$typeClass}::{$constantName}");
        self::assertSame($this->getExpectedTypeName(), $typeName);
        self::assertSame($typeName, \constant("$typeClass::$constantName"));
        self::assertSame($this->createSut()->getName(), $this->getExpectedTypeName());
    }

    /**
     * @param string $className
     * @return string
     */
    protected function convertToTypeName(string $className): string
    {
        $withoutType = \preg_replace('~Type$~', '', $className);
        $parts = \explode('\\', $withoutType);
        $baseClassName = end($parts);
        \preg_match_all('~(?<words>[A-Z][^A-Z]+)~', $baseClassName, $matches);
        $concatenated = \implode('_', $matches['words']);

        return \strtolower($concatenated);
    }

    /**
     * @return Type|string
     */
    protected function getRegisteredClass(): string
    {
        $registeredClass = \preg_replace('~Type$~', '', $this->getTypeClass());
        if (\class_exists($registeredClass)) {
            return $registeredClass;
        }
        $withoutEnumTypes = \preg_replace('~\\\EnumTypes\\\~', '\\', $registeredClass);
        self::assertTrue(
            \class_exists($withoutEnumTypes),
            "Estimated registered enum classes {$registeredClass} not {$withoutEnumTypes} do not exist"
        );

        return $withoutEnumTypes;
    }

    /**
     * @test
     * @expectedException \Doctrineum\SelfRegisteringType\Exceptions\TypeNameOccupied
     * @expectedExceptionMessageRegExp ~IAmUsingOccupiedName~
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_not_accidentally_replace_type_by_another_of_same_name(): void
    {
        $typeClass = $this->getTypeClass();
        $typeClass::registerSelf();

        IAmUsingOccupiedName::overloadNameForTestingPurpose($this->getExpectedTypeName());
        IAmUsingOccupiedName::registerSelf();
    }

}

/** @inner */
class IAmUsingOccupiedName extends AbstractSelfRegisteringType
{
    private static $overloadedName;

    public static function overloadNameForTestingPurpose(string $name): void
    {
        self::$overloadedName = $name;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return '';
    }

    public function getName(): string
    {
        return self::$overloadedName;
    }

}