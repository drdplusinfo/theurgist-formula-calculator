<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\EnumTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrineum\Tests\SelfRegisteringType\AbstractSelfRegisteringTypeTest;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\Property;

abstract class AbstractTestOfPropertyType extends AbstractSelfRegisteringTypeTest
{

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function Type_can_be_converted_to_PHP_value(): void
    {
        $propertyTypeClass = $this->getTypeClass();
        $propertyTypeClass::registerSelf();
        $propertyType = Type::getType($this->getExpectedTypeName());
        $phpValue = $propertyType->convertToPHPValue($value = $this->getValue(), $this->getPlatform());
        self::assertInstanceOf($this->getRegisteredClass(), $phpValue);
        self::assertEquals($value, (string)$phpValue);
    }

    abstract protected function getValue();

    protected function getRegisteredClass(): string
    {
        $propertyTypeClass = $this->getTypeClass();
        $propertyClass = preg_replace('~\\\(\w+)\\\(\w+)Type$~', '\\\$2', $propertyTypeClass);

        return $propertyClass;
    }

    /**
     * @return \Mockery\MockInterface|AbstractPlatform
     */
    private function getPlatform()
    {
        return \Mockery::mock(AbstractPlatform::class);
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_get_same_type_name_as_property_code(): void
    {
        $propertyClass = $this->getRegisteredClass();
        /** @var Property $property */
        $property = (new \ReflectionClass($propertyClass))->newInstanceWithoutConstructor();
        self::assertInstanceOf(PropertyCode::class, $property->getCode());
        self::assertSame($this->getExpectedTypeName(), $property->getCode()->getValue());
        $constantName = strtoupper($this->getExpectedTypeName());
        self::assertTrue(\defined(PropertyCode::class . '::' . $constantName));
        self::assertSame(\constant(PropertyCode::class . '::' . $constantName), $property->getCode()->getValue());
    }
}