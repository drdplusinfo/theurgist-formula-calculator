<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Tests\Codes\EnumTypes;

use DrdPlus\Codes\Partials\AbstractCode;

abstract class AbstractCodeTypeWithSubTypesOnlyTest extends AbstractCodeTypeTest
{
    private $valuesFromDb;

    /**
     * @test
     */
    public function I_get_default_enum_class_if_subtype_regexp_does_not_match(): void
    {
        self::assertFalse(false, static::getSutClass() . ' does not have default enum class, only sub-type enums');
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\EnumClassNotFound
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_get_enum_with_empty_string_on_conversion(): void
    {
        // There is no default enum class for a type with sub-types only, so we expected an exception
        parent::I_get_enum_with_empty_string_on_conversion();
    }

    protected function turnToValuesFromDb(string ...$codeClasses): array
    {
        $valuesForDb = [];
        foreach ($codeClasses as $codeClass) {
            self::assertTrue(\is_a($codeClass, AbstractCode::class, true), "Given class $codeClass should be child of " . AbstractCode::class);
            /** @noinspection PhpUndefinedMethodInspection */
            $valuesForDbFromCodeClass = \array_map(
                function (string $value) use ($codeClass) {
                    return $codeClass . '::' . $value;
                },
                $codeClass::getPossibleValues() // for example
            );
            foreach ($valuesForDbFromCodeClass as $valueForDbFromCodeClass) {
                $valuesForDb[] = [$valueForDbFromCodeClass];
            }
        }

        return $valuesForDb;
    }

    protected function getSomeValueFromDatabaseForEnum(): string
    {
        $someWrappedValues = $this->provideValuesFromDb();
        $someWrappedValueKey = \array_rand($someWrappedValues);

        return $someWrappedValues[$someWrappedValueKey][0];
    }

    protected function getSomeEnumValue(): string
    {
        return $this->parseEnumValueFromDatabaseValue($this->getSomeValueFromDatabaseForEnum());
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function provideValuesFromDb(): array
    {
        if ($this->valuesFromDb === null) {
            $this->valuesFromDb = $this->turnToValuesFromDb(...$this->getRelatedCodeClasses());
        }

        return $this->valuesFromDb;
    }

    /**
     * @test
     */
    public function Code_value_is_prefixed_by_its_class_name_for_database_persistence(): void
    {
        self::assertFalse(
            false,
            $this->getRegisteredClass() . ' can not give itself as it is ' . (\interface_exists($this->getRegisteredClass())
                ? 'interface'
                : 'abstract class'
            )
        );
    }
}