<?php declare(strict_types=1);

namespace DrdPlus\Tests\BaseProperties\Partials;

use DrdPlus\BaseProperties\Partials\AbstractIntegerProperty;

abstract class AbstractIntegerPropertyTest extends AbstractSimplePropertyTest
{
    /**
     * @return int[]
     */
    protected function getValuesForTest(): array
    {
        return [0, 123456];
    }

    /**
     * @test
     */
    public function I_can_add_value(): void
    {
        /** @var AbstractIntegerProperty $propertyClass */
        $propertyClass = self::getSutClass();
        /** @var AbstractIntegerProperty $property */
        $property = $propertyClass::getIt(123);
        self::assertSame(123, $property->getValue());

        $greater = $property->add(456);
        self::assertNotEquals($property, $greater);
        self::assertSame(579, $greater->getValue());

        $double = $greater->add($greater);
        self::assertNotEquals($property, $double);
        self::assertSame(1158, $double->getValue());
    }

    /**
     * @test
     */
    public function I_can_subtract_value(): void
    {
        /** @var AbstractIntegerProperty $propertyClass */
        $propertyClass = self::getSutClass();
        /** @var AbstractIntegerProperty $property */
        $property = $propertyClass::getIt(123);
        self::assertSame(123, $property->getValue());

        $lesser = $property->sub(456);
        self::assertNotEquals($property, $lesser);
        self::assertSame(-333, $lesser->getValue());

        $zero = $lesser->sub($lesser);
        self::assertNotEquals($property, $zero);
        self::assertSame(0, $zero->getValue());
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function Has_modifying_methods_return_value_annotated(): void
    {
        $reflectionClass = new \ReflectionClass(self::getSutClass());
        $classBasename = \str_replace($reflectionClass->getNamespaceName() . '\\', '', $reflectionClass->getName());
        self::assertStringContainsString(<<<ANNOTATION
 * @method static {$classBasename} getIt(int | \\Granam\\Integer\\IntegerInterface \$value)
 * @method {$classBasename} add(int | \\Granam\\Integer\\IntegerInterface \$value)
 * @method {$classBasename} sub(int | \\Granam\\Integer\\IntegerInterface \$value)
ANNOTATION
            , $reflectionClass->getDocComment());
    }

    /**
     * @test
     */
    public function Same_instance_is_returned_for_same_value()
    {
        /** @var AbstractIntegerProperty $propertyClass */
        $propertyClass = self::getSutClass();
        /** @var AbstractIntegerProperty $property */
        $property = $propertyClass::getIt(123);
        self::assertSame(123, $property->getValue());
        self::assertSame($property, $propertyClass::getIt(123));
    }
}