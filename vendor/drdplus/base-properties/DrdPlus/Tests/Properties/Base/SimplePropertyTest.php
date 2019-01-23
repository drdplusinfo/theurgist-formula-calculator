<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\Base;

use DrdPlus\Properties\Property;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tests\Properties\PropertyTest;

abstract class SimplePropertyTest extends PropertyTest
{

    /**
     * @test
     */
    public function I_can_get_property_easily(): void
    {
        /** @var Property|string $sutClass */
        $sutClass = self::getSutClass();
        foreach ((array)$this->getValuesForTest() as $value) {
            $property = $sutClass::getIt($value);
            self::assertInstanceOf($sutClass, $property);
            /** @var Property $property */
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