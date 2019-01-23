<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\Base;

use Doctrineum\Scalar\ScalarEnumType;
use DrdPlus\Codes\Properties\PropertyCode;

abstract class AbstractStoredPropertyTest extends SimplePropertyTest
{
    use ItHasProperlyAnnotatedCodeGetter;

    /**
     * @return string
     */
    protected function getExpectedCodeClass(): string
    {
        return PropertyCode::class;
    }

    /**
     * @test
     */
    public function I_can_register_it_as_enum(): void
    {
        $basename = $this->getSutBaseName();
        $namespace = $this->getPropertyNamespace();
        $typeClass = $namespace . '\\EnumTypes\\' . $basename . 'Type';
        self::assertTrue(\class_exists($typeClass), "Missing enum type class {$typeClass}");
        self::assertTrue(
            \is_a($namespace . '\\EnumTypes\\' . $basename . 'Type', ScalarEnumType::class, true),
            $namespace . '\\EnumTypes\\' . $basename . 'Type should be a descendant of ' . ScalarEnumType::class
        );
    }
}