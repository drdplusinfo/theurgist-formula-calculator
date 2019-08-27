<?php declare(strict_types=1);

namespace DrdPlus\Tests\BaseProperties\Partials;

use DrdPlus\BaseProperties\BaseProperty;
use DrdPlus\Codes\Properties\PropertyCode;

abstract class AbstractSimplePropertyTest extends PropertyTest
{
    /**
     * @test
     */
    public function I_can_get_property_easily()
    {
        /** @var BaseProperty|string $sutClass */
        $sutClass = self::getSutClass();
        foreach ((array)$this->getValuesForTest() as $value) {
            $property = $sutClass::getIt($value);
            self::assertInstanceOf($sutClass, $property);
            /** @var BaseProperty $property */
            self::assertSame((string)$value, (string)$property->getValue());
            self::assertSame((string)$value, (string)$property);
            self::assertSame(
                PropertyCode::getIt($this->getExpectedPropertyCode()),
                $property->getCode(),
                'We expected ' . PropertyCode::class . " with value '{$this->getExpectedPropertyCode()}'"
                . ', got ' . \get_class($property->getCode()) . " with value '{$property->getCode()->getValue()}'"
            );
        }
    }

    /**
     * @return array|int[]|float[]|string[]
     */
    abstract protected function getValuesForTest(): array;

}