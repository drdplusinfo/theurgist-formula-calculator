<?php declare(strict_types=1);

namespace DrdPlus\Tests\BaseProperties\Partials;

use DrdPlus\Codes\Partials\AbstractCode;
use DrdPlus\BaseProperties\Property;
use DrdPlus\Codes\Properties\PropertyCode;
use Granam\Tests\Tools\TestWithMockery;

abstract class PropertyTest extends TestWithMockery
{

    /**
     * @test
     */
    abstract public function I_can_get_property_easily();

    /**
     * @test
     */
    public function I_can_use_it_as_generic_group_property(): void
    {
        $propertyClass = self::getSutClass();
        foreach ($this->getGenericGroupsPropertyClassNames() as $genericGroupsPropertyClassName) {
            self::assertTrue(
                \is_a($propertyClass, $genericGroupsPropertyClassName, true),
                $propertyClass . ' does not belongs into ' . $genericGroupsPropertyClassName
            );
        }
    }

    protected function getGenericGroupsPropertyClassNames(): array
    {
        return [Property::class];
    }

    protected function getPropertyNamespace(): string
    {
        return \preg_replace('~[\\\]\w+$~', '', self::getSutClass());
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_get_code(): void
    {
        $reflectionClass = new \ReflectionClass(self::getSutClass());
        /** @var Property $sut */
        $sut = $reflectionClass->newInstanceWithoutConstructor();
        /** @var AbstractCode $codeClass */
        $codeClass = $this->getExpectedCodeClass();
        self::assertSame($codeClass::getIt($this->getExpectedPropertyCode()), $sut->getCode());
    }

    protected function getExpectedPropertyCode(): string
    {
        $propertyBaseName = $this->getSutBaseName();
        $underScoredClassName = \preg_replace('~(\w)([A-Z])~', '$1_$2', $propertyBaseName);

        return \strtolower($underScoredClassName);
    }

    protected function getSutBaseName(): string
    {
        return \preg_replace('~^.*[\\\]([^\\\]+)$~', '$1', self::getSutClass());
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function Code_getter_has_proper_return_type(): void
    {
        $getter = new \ReflectionMethod(self::getSutClass(), 'getCode');
        $returnType = $getter->getReturnType();
        self::assertNotNull(
            $returnType,
            \sprintf('Method %s::getCode does not have return type', self::getSutClass())
        );
        self::assertSame(
            $this->getExpectedCodeClass(),
            $returnType->getName(),
            \sprintf('Method %s::getCode does not have expected return type', self::getSutClass())
        );
    }

    protected function getExpectedCodeClass(): string
    {
        return PropertyCode::class;
    }
}