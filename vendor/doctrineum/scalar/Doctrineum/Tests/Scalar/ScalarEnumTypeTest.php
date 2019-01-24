<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Tests\Scalar;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrineum\Scalar\ScalarEnum;
use Doctrineum\Scalar\ScalarEnumInterface;
use Doctrineum\Scalar\ScalarEnumType;
use Doctrineum\Tests\Scalar\Helpers\EnumTypes\EnumWithSubNamespaceType;
use Doctrineum\Tests\Scalar\Helpers\EnumTypes\IShouldHaveTypeKeywordOnEnd;
use Doctrineum\Tests\Scalar\Helpers\EnumTypes\WithoutEnumIsThisType;
use Doctrineum\Tests\Scalar\Helpers\EnumWithSubNamespace;
use Doctrineum\Tests\Scalar\Helpers\TestOfAbstractScalarEnum;
use Doctrineum\Tests\Scalar\Helpers\TestSubTypeScalarEnum;
use Doctrineum\Tests\Scalar\Helpers\WithToStringTestObject;
use Doctrineum\Tests\SelfRegisteringType\AbstractSelfRegisteringTypeTest;
use Granam\Scalar\ScalarInterface;

/**
 * @method ScalarEnumType createSut()
 */
class ScalarEnumTypeTest extends AbstractSelfRegisteringTypeTest
{

    /**
     * This is called after every test
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if (Type::hasType($this->getExpectedTypeName())) {
            $enumType = Type::getType($this->getExpectedTypeName());
            /** @var ScalarEnumType $enumType */
            if ($enumType::hasSubTypeEnum($this->getSubTypeEnumClass())) {
                self::assertTrue($enumType::removeSubTypeEnum($this->getSubTypeEnumClass()));
            }
        }
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_get_false_if_self_registering_it_again(): void
    {
        $typeClass = $this->getTypeClass();
        $typeClass::registerSelf();
        self::assertTrue(Type::hasType($this->getExpectedTypeName()));
        self::assertFalse($typeClass::registerSelf());
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function Its_sql_declaration_is_valid(): void
    {
        $platform = $this->getPlatform();
        $sql = $this->createSut()->getSQLDeclaration([], $platform);
        self::assertSame('VARCHAR(64)', $sql);
    }

    /**
     * @return AbstractPlatform|\Mockery\MockInterface
     */
    protected function getPlatform(): AbstractPlatform
    {
        return $this->mockery(AbstractPlatform::class);
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function Null_to_database_value_is_null(): void
    {
        $platform = $this->getPlatform();
        self::assertNull($this->createSut()->convertToDatabaseValue(null, $platform));
    }

    /**
     * @test
     * @param string|int|float|bool|null $value = null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function Enum_as_database_value_is_string_value_of_that_enum($value = null): void
    {
        $value = $value ?? 'foo';
        $platform = $this->getPlatform();
        $enumClass = $this->getRelatedEnumClass();
        self::assertSame($value, $this->createSut()->convertToDatabaseValue($enumClass::getEnum($value), $platform));
    }

    /**
     * @return string|ScalarEnum
     */
    protected function getRelatedEnumClass(): string
    {
        return \preg_replace('~(?:EnumTypes\\\)?(\w+)Type$~', '$1', self::getSutClass());
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function null_from_database_gives_null(): void
    {
        $platform = $this->getPlatform();
        self::assertNull($this->createSut()->convertToPHPValue(null, $platform));
    }

    /**
     * @test
     * @dataProvider provideValuesFromDb
     * @param mixed $valueFromDb = null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function Scalar_value_is_converted_to_enum_with_that_value($valueFromDb = null): void
    {
        $platform = $this->getPlatform();
        $enum = $this->createSut()->convertToPHPValue($valueFromDb, $platform);
        if ($valueFromDb === null) {
            self::assertNull($enum);
        } else {
            self::assertInstanceOf($this->getRegisteredClass(), $enum);
            self::assertSame($valueFromDb, $enum->getValue());
        }
    }

    public function provideValuesFromDb(): array
    {
        return [
            [-15],
            [456.789],
            ['foo'],
            [true]
        ];
    }

    /**
     * @test
     * @dataProvider provideValuesFromDb
     * @param mixed $valueFromDb = null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function Scalar_value_is_converted_to_enum_subtype_with_that_value($valueFromDb = null): void
    {
        $platform = $this->getPlatform();
        /** @var ScalarEnumType $scalaEnumTypeClass */
        $scalaEnumTypeClass = static::getSutClass();
        $scalaEnumTypeClass::registerSubTypeEnum($this->getSubTypeEnumClass(), '~^.*$~' /* everything goes to sub-type */);
        $enum = $this->createSut()->convertToPHPValue($valueFromDb, $platform);
        if ($valueFromDb === null) {
            self::assertNull($enum);
        } else {
            self::assertInstanceOf($this->getSubTypeEnumClass(), $enum);
            self::assertSame($valueFromDb, $enum->getValue());
        }
        $scalaEnumTypeClass::removeSubTypeEnum($this->getSubTypeEnumClass());
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_get_enum_with_empty_string_on_conversion(): void
    {
        $platform = $this->getPlatform();
        $enum = $this->createSut()->convertToPHPValue($emptyString = '', $platform);
        self::assertInstanceOf($this->getRegisteredClass(), $enum);
        self::assertSame($emptyString, $enum->getValue());
    }

    // CONVERSION-TO-PHP TESTS

    /**
     * The Enum class does NOT cast non-string scalars into string (integers, floats etc).
     * (But saving the value into database and pulling it back probably will.)
     *
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_get_pure_integer_in_enum(): void
    {
        $platform = $this->getPlatform();
        $enum = $this->createSut()->convertToPHPValue($integer = 12345, $platform);
        self::assertInstanceOf($this->getRegisteredClass(), $enum);
        self::assertSame($integer, $enum->getValue());
    }

    /**
     * The Enum class does NOT cast non-string scalars into string (integers, floats etc).
     * (But saving the value into database and pulling it back probably will.)
     *
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_get_enum_with_pure_integer_zero(): void
    {
        $platform = $this->getPlatform();
        $enum = $this->createSut()->convertToPHPValue($zeroInteger = 0, $platform);
        self::assertInstanceOf($this->getRegisteredClass(), $enum);
        self::assertSame($zeroInteger, $enum->getValue());
    }

    /**
     * The Enum class does NOT cast non-string scalars into string (integers, floats etc).
     * (But saving the value into database and pulling it back probably will.)
     *
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_get_enum_with_pure_float(): void
    {
        $platform = $this->getPlatform();
        $enum = $this->createSut()->convertToPHPValue($float = 12345.6789, $platform);
        self::assertInstanceOf($this->getRegisteredClass(), $enum);
        self::assertSame($float, $enum->getValue());
    }

    /**
     * The Enum class does NOT cast non-string scalars into string (integers, floats etc).
     * (But saving the value into database and pulling it back probably will.)
     *
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_get_enum_with_pure_float_zero(): void
    {
        $platform = $this->getPlatform();
        $enum = $this->createSut()->convertToPHPValue($zeroFloat = 0.0, $platform);
        self::assertInstanceOf($this->getRegisteredClass(), $enum);
        self::assertSame($zeroFloat, $enum->getValue());
    }

    /**
     * The Enum class does NOT cast non-string scalars into string (integers, floats etc).
     * (But saving the value into database and pulling it back probably will.)
     *
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_get_enum_with_pure_false(): void
    {
        $platform = $this->getPlatform();
        $enum = $this->createSut()->convertToPHPValue($false = false, $platform);
        self::assertInstanceOf($this->getRegisteredClass(), $enum);
        self::assertSame($false, $enum->getValue());
    }

    /**
     * The Enum class does NOT cast non-string scalars into string (integers, floats etc).
     * (But saving the value into database and pulling it back probably will.)
     *
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_get_enum_with_pure_true(): void
    {
        $platform = $this->getPlatform();
        $enum = $this->createSut()->convertToPHPValue($true = true, $platform);
        self::assertInstanceOf($this->getRegisteredClass(), $enum);
        self::assertSame($true, $enum->getValue());
    }

    /**
     * @test
     * @param ScalarInterface $toStringObject = null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function object_with_to_string_to_php_value_is_enum_with_that_string(ScalarInterface $toStringObject = null): void
    {
        $platform = $this->getPlatform();
        $value = $toStringObject ? $toStringObject->__toString() : 'foo';
        $enum = $this->createSut()->convertToPHPValue($toStringObject ?? new WithToStringTestObject($value), $platform);
        self::assertInstanceOf($this->getRegisteredClass(), $enum);
        self::assertSame($value, $enum->getValue());
        self::assertSame($value, (string)$enum);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     * @expectedExceptionMessageRegExp ~array~
     * @throws \Doctrine\DBAL\DBALException
     */
    public function array_to_php_value_cause_exception(): void
    {
        $platform = $this->getPlatform();
        $enumType = $this->createSut();
        /** @noinspection PhpParamsInspection */
        $enumType->convertToPHPValue([], $platform);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     * @throws \Doctrine\DBAL\DBALException
     */
    public function resource_to_php_value_cause_exception(): void
    {
        $platform = $this->getPlatform();
        $this->createSut()->convertToPHPValue(tmpfile(), $platform);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     * @throws \Doctrine\DBAL\DBALException
     */
    public function object_to_php_value_cause_exception(): void
    {
        $platform = $this->getPlatform();
        $this->createSut()->convertToPHPValue(new \stdClass(), $platform);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     * @throws \Doctrine\DBAL\DBALException
     */
    public function callback_to_php_value_cause_exception(): void
    {
        $platform = $this->getPlatform();
        $this->createSut()->convertToPHPValue(function () {
        }, $platform);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\UnexpectedValueToDatabaseValue
     * @throws \Doctrine\DBAL\DBALException
     */
    public function conversion_of_non_object_to_database_cause_exception(): void
    {
        $enumType = Type::getType($this->getExpectedTypeName());
        $enumType->convertToDatabaseValue('foo', $this->getPlatform());
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\UnexpectedValueToDatabaseValue
     * @throws \Doctrine\DBAL\DBALException
     */
    public function conversion_of_non_enum_to_database_cause_exception(): void
    {
        $enumType = Type::getType($this->getExpectedTypeName());
        $enumType->convertToDatabaseValue(new \stdClass(), $this->getPlatform());
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_get_same_enum_type_name_as_enum_type_instance_name(): void
    {
        $enumType = Type::getType($this->getExpectedTypeName());
        self::assertSame($this->getExpectedTypeName(), $enumType->getName());
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function It_requires_sql_comment_hint(): void
    {
        $enumType = Type::getType($this->getExpectedTypeName());
        self::assertTrue($enumType->requiresSQLCommentHint($this->getPlatform()));
    }

    // SUBTYPE TESTS

    /**
     * @test
     */
    public function I_can_register_subtype(): void
    {
        /** @var ScalarEnumType $enumType */
        $enumType = self::getSutClass();
        self::assertTrue($enumType::addSubTypeEnum($this->getSubTypeEnumClass(), $regexp = '~foo~'));
        self::assertTrue($enumType::hasSubTypeEnum($this->getSubTypeEnumClass()));

        self::assertFalse($enumType::registerSubTypeEnum($this->getSubTypeEnumClass(), $regexp));
        self::assertTrue($enumType::removeSubTypeEnum($this->getSubTypeEnumClass()));
        self::assertTrue($enumType::registerSubTypeEnum($this->getSubTypeEnumClass(), $regexp));
    }

    /**
     * @return string|ScalarEnumType
     */
    protected function getSubTypeEnumClass(): string
    {
        return TestSubTypeScalarEnum::class;
    }

    /**
     * @test
     */
    public function I_can_remove_subtype(): void
    {
        /** @var ScalarEnumType $enumType */
        $enumType = self::getSutClass();
        self::assertFalse($enumType::hasSubTypeEnum($this->getSubTypeEnumClass()));
        self::assertTrue($enumType::addSubTypeEnum($this->getSubTypeEnumClass(), '~foo~'));
        self::assertTrue($enumType::removeSubTypeEnum($this->getSubTypeEnumClass()));
        self::assertFalse($enumType::hasSubTypeEnum($this->getSubTypeEnumClass()));
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\SubTypeEnumIsNotRegistered
     */
    public function I_can_not_remove_not_registered_subtype(): void
    {
        /** @var ScalarEnumType $enumType */
        $enumType = self::getSutClass();
        self::assertFalse($enumType::hasSubTypeEnum($this->getSubTypeEnumClass()));
        self::assertTrue($enumType::addSubTypeEnum($this->getSubTypeEnumClass(), '~foo~'));
        self::assertTrue($enumType::removeSubTypeEnum($this->getSubTypeEnumClass()));
        self::assertTrue($enumType::removeSubTypeEnum($this->getSubTypeEnumClass())); // twice the same
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_get_registered_subtype_enum_on_match(): void
    {
        /** @var ScalarEnumType $enumType */
        $enumType = $this->createSut();
        self::assertTrue($enumType::addSubTypeEnum($this->getSubTypeEnumClass(), $regexp = '~some specific string~'));
        /** @var AbstractPlatform $abstractPlatform */
        $abstractPlatform = $this->getPlatform();
        $matchingValueToConvert = 'A string with some specific string inside.';
        self::assertRegExp($regexp, $matchingValueToConvert);
        /**
         * Used TestSubTypeEnum returns as an "enum" the given value, which is $valueToConvert in this case,
         *
         * @see \Doctrineum\Tests\Scalar\TestSubTypeEnum::getEnum
         */
        $enumFromSubType = $enumType->convertToPHPValue($matchingValueToConvert, $abstractPlatform);
        self::assertInstanceOf($this->getSubTypeEnumClass(), $enumFromSubType);
        self::assertSame($matchingValueToConvert, (string)$enumFromSubType);
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_get_default_enum_class_if_subtype_regexp_does_not_match(): void
    {
        /** @var ScalarEnumType $enumType */
        $enumType = $this->createSut();
        self::assertTrue($enumType::addSubTypeEnum($this->getSubTypeEnumClass(), $regexp = '~some specific string~'));
        $platform = $this->getPlatform();
        $nonMatchingValue = 'A string without that specific string.';
        self::assertNotRegExp($regexp, $nonMatchingValue);
        /**
         * Used TestSubTypeEnum returns as an "enum" the given value, which is $valueToConvert in this case,
         *
         * @see \Doctrineum\Tests\Scalar\TestSubTypeEnum::getEnum
         */
        $enum = $enumType->convertToPHPValue($nonMatchingValue, $platform);
        self::assertInstanceOf(ScalarEnumInterface::class, $enum);
        self::assertNotInstanceOf($this->getSubTypeEnumClass(), $enum);
        self::assertSame($nonMatchingValue, (string)$enum);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\SubTypeEnumIsAlreadyRegistered
     */
    public function registering_same_subtype_again_throws_exception(): void
    {
        /** @var ScalarEnumType $enumType */
        $enumType = self::getSutClass();
        self::assertFalse($enumType::hasSubTypeEnum($this->getSubTypeEnumClass()));
        self::assertTrue($enumType::addSubTypeEnum($this->getSubTypeEnumClass(), '~foo~'));
        // registering twice - should thrown an exception
        $enumType::addSubTypeEnum($this->getSubTypeEnumClass(), '~foo~');
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\SubTypeEnumIsAlreadyRegistered
     * @expectedExceptionMessageRegExp /~foo~.*~bar~/
     */
    public function I_can_not_register_same_subtype_by_easy_registrar_with_different_regexp(): void
    {
        /** @var ScalarEnumType $enumType */
        $enumType = self::getSutClass();
        self::assertFalse($enumType::hasSubTypeEnum($this->getSubTypeEnumClass()));
        self::assertTrue($enumType::registerSubTypeEnum($this->getSubTypeEnumClass(), '~foo~'));
        try {
            self::assertFalse($enumType::registerSubTypeEnum($this->getSubTypeEnumClass(), '~foo~'));
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getTraceAsString());
        }
        $enumType::registerSubTypeEnum($this->getSubTypeEnumClass(), '~bar~');
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\SubTypeEnumClassNotFound
     */
    public function I_can_not_register_non_existing_type(): void
    {
        /** @var ScalarEnumType $enumType */
        $enumType = self::getSutClass();
        $enumType::addSubTypeEnum('whoAmI', '~foo~');
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\SubTypeEnumHasToBeEnum
     */
    public function I_can_not_register_invalid_subtype_class(): void
    {
        /** @var ScalarEnumType $enumType */
        $enumType = self::getSutClass();
        $enumType::addSubTypeEnum('stdClass', '~foo~');
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\InvalidRegexpFormat
     * @expectedExceptionMessage The given regexp is not enclosed by same delimiters and therefore is not valid: 'foo~'
     */
    public function I_can_not_register_subtype_with_invalid_regexp(): void
    {
        /** @var ScalarEnumType $enumType */
        $enumType = self::getSutClass();
        $enumType::addSubTypeEnum(
            $this->getSubTypeEnumClass(),
            'foo~' // missing opening delimiter
        );
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function Subtypes_with_same_regexp_but_different_parent_types_lives_separately(): void
    {
        /** @var ScalarEnumType $enumTypeClass */
        $enumTypeClass = $this->getTypeClass();
        $regexp = '~searching pattern~';
        $matchingValue = 'some string fitting to "searching pattern"';
        self::assertRegExp($regexp, $matchingValue);

        // first sub-type
        if ($enumTypeClass::hasSubTypeEnum($this->getSubTypeEnumClass())) {
            $enumTypeClass::removeSubTypeEnum($this->getSubTypeEnumClass());
        }
        $enumTypeClass::addSubTypeEnum($this->getSubTypeEnumClass(), $regexp);

        // second sub-type
        $anotherEnumTypeClass = $this->getAnotherEnumTypeClass();
        if ($anotherEnumTypeClass::hasSubTypeEnum($this->getAnotherSubTypeEnumClass())) {
            $anotherEnumTypeClass::removeSubTypeEnum($this->getAnotherSubTypeEnumClass());
        }
        // regexp is same but sub-type AND enum class are NOT
        $anotherEnumTypeClass::addSubTypeEnum($this->getAnotherSubTypeEnumClass(), $regexp);

        $enumType = Type::getType($this->getExpectedTypeName());
        $enumSubType = $enumType->convertToPHPValue($matchingValue, $this->getPlatform());
        self::assertInstanceOf($this->getSubTypeEnumClass(), $enumSubType);
        self::assertSame($matchingValue, (string)$enumSubType);

        TestAnotherScalarEnumType::registerSelf();
        $anotherEnumType = Type::getType(TestAnotherScalarEnumType::DIFFERENT_NAME);
        $anotherEnumSubType = $anotherEnumType->convertToPHPValue($matchingValue, $this->getPlatform());
        self::assertInstanceOf($this->getAnotherSubTypeEnumClass(), $anotherEnumSubType);
        self::assertSame($matchingValue, (string)$anotherEnumSubType);

        // registered sub-types were different, just regexp was the same - let's test if they are kept separately
        self::assertNotSame($enumSubType, $anotherEnumSubType);
    }

    /**
     * @return string|TestAnotherScalarEnumType
     */
    protected function getAnotherEnumTypeClass(): string
    {
        return TestAnotherScalarEnumType::class;
    }

    /**
     * @return string|TestAnotherSubTypeScalarEnum
     */
    protected function getAnotherSubTypeEnumClass(): string
    {
        return TestAnotherSubTypeScalarEnum::class;
    }

    /**
     * Warning, this behaviour is undefined.
     *
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_register_subtypes_with_same_regexp(): void
    {
        /** @var ScalarEnumType $enumTypeClass */
        $enumTypeClass = $this->getTypeClass();
        $regexp = '~searching pattern~';
        $matchingValue = 'some string fitting to "searching pattern"';
        self::assertRegExp($regexp, $matchingValue);

        // first sub-type
        if ($enumTypeClass::hasSubTypeEnum($this->getSubTypeEnumClass())) {
            $enumTypeClass::removeSubTypeEnum($this->getSubTypeEnumClass());
        }
        $enumTypeClass::addSubTypeEnum($this->getSubTypeEnumClass(), $regexp);

        // second sub-type
        if ($enumTypeClass::hasSubTypeEnum($this->getAnotherSubTypeEnumClass())) {
            $enumTypeClass::removeSubTypeEnum($this->getAnotherSubTypeEnumClass());
        }
        // regexp AND enum class are same but sub-type is NOT
        $enumTypeClass::addSubTypeEnum($this->getAnotherSubTypeEnumClass(), $regexp);

        $enumType = Type::getType($this->getExpectedTypeName());
        $enumSubType = $enumType->convertToPHPValue($matchingValue, $this->getPlatform());
        self::assertInstanceOf($this->getSubTypeEnumClass(), $enumSubType);
        self::assertSame($matchingValue, (string)$enumSubType);

        $anotherEnumSubType = $enumType->convertToPHPValue($matchingValue, $this->getPlatform());
        self::assertInstanceOf($this->getSubTypeEnumClass(), $anotherEnumSubType);
        self::assertSame($matchingValue, (string)$anotherEnumSubType);
        // despite their DIFFERENT sub-type classes the result is unwillingly the same because of same regexp
        self::assertSame(
            $enumSubType,
            $anotherEnumSubType,
            'Sub-type enum class ' . $this->getSubTypeEnumClass() . ' should return the very same instance for same value'
        );
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_use_subtype(): void
    {
        ScalarEnumType::addSubTypeEnum($this->getSubTypeEnumClass(), $pattern = '~foo~');
        self::assertRegExp($pattern, $enumValue = 'foo bar baz');
        $enumBySubType = ScalarEnumType::getType(ScalarEnumType::SCALAR_ENUM)
            ->convertToPHPValue($enumValue, $this->getPlatform());
        self::assertInstanceOf($this->getSubTypeEnumClass(), $enumBySubType);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\InvalidRegexpFormat
     * @expectedExceptionMessageRegExp ~/foo~i
     */
    public function I_can_not_add_subtype_with_invalid_regexp(): void
    {
        ScalarEnumType::addSubTypeEnum($this->getSubTypeEnumClass(), '/foo');
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_use_enum_type_from_sub_namespace(): void
    {
        EnumWithSubNamespaceType::registerSelf();
        $enum = EnumWithSubNamespaceType::getType(EnumWithSubNamespaceType::WITH_SUB_NAMESPACE)
            ->convertToPHPValue('foo', $this->getPlatform());
        self::assertInstanceOf(EnumWithSubNamespace::class, $enum);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\EnumClassNotFound
     * @expectedExceptionMessageRegExp ~foo~
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_am_stopped_by_exception_on_conversion_to_unknown_enum(): void
    {
        WithoutEnumIsThisType::registerSelf();
        $type = WithoutEnumIsThisType::getType(WithoutEnumIsThisType::WITHOUT_ENUM_IS_THIS_TYPE);
        $type->convertToPHPValue('foo', $this->getPlatform());
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\CouldNotDetermineEnumClass
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_can_not_use_type_with_unexpected_name_structure(): void
    {
        IShouldHaveTypeKeywordOnEnd::registerSelf();
        $type = IShouldHaveTypeKeywordOnEnd::getType(IShouldHaveTypeKeywordOnEnd::I_SHOULD_HAVE_TYPE_KEYWORD_ON_END);
        $type->convertToPHPValue('foo', $this->getPlatform());
    }

    /**
     * @test
     * @expectedException  \Doctrineum\Scalar\Exceptions\InvalidRegexpFormat
     * @expectedExceptionMessageRegExp ~bar~
     */
    public function I_can_not_ask_for_registered_subtype_by_invalid_regexp(): void
    {
        /** @var ScalarEnumType $enumType */
        $enumType = self::getSutClass();
        $enumType::addSubTypeEnum($this->getSubTypeEnumClass(), '~foo~');
        $enumType::hasSubTypeEnum($this->getSubTypeEnumClass(), '~bar'); // intentionally missing trailing tilde
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\CanNotCreateInstanceOfAbstractEnum
     * @expectedExceptionMessageRegExp ~foo.+TestOfAbstractScalarEnum~
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_got_descriptive_exception_message_if_abstract_class_for_enum_is_used(): void
    {
        WithOwnDefaultEnumClass::setDefaultEnumClass(TestOfAbstractScalarEnum::class);
        WithOwnDefaultEnumClass::registerSelf();
        $withOwnDefaultEnumClass = WithOwnDefaultEnumClass::getType(WithOwnDefaultEnumClass::WITH_OWN_DEFAULT_ENUM_CLASS);
        $withOwnDefaultEnumClass->convertToPHPValue('foo', $this->getPlatform());
    }
}

class TestAnotherSubTypeScalarEnum extends ScalarEnum
{

}

class TestAnotherScalarEnumType extends ScalarEnumType
{
    public const DIFFERENT_NAME = 'different_name';

    public function getName(): string
    {
        return self::DIFFERENT_NAME;
    }
}

class IAmUsingOccupiedName extends ScalarEnumType
{
    // without overwriting parent name
}

class WithOwnDefaultEnumClass extends ScalarEnumType
{
    public const WITH_OWN_DEFAULT_ENUM_CLASS = 'WITH_OWN_DEFAULT_ENUM_CLASS';

    private static $defaultEnumClass;

    public static function setDefaultEnumClass(string $defaultEnumClass): void
    {
        self::$defaultEnumClass = $defaultEnumClass;
    }

    protected static function getDefaultEnumClass($enumValue): string
    {
        return self::$defaultEnumClass ?? parent::getDefaultEnumClass($enumValue);
    }

    public function getName(): string
    {
        return self::WITH_OWN_DEFAULT_ENUM_CLASS;
    }

}