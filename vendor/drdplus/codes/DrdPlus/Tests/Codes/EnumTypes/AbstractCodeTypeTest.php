<?php
namespace DrdPlus\Tests\Codes\EnumTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrineum\Scalar\Exceptions\EnumClassNotFound;
use Doctrineum\Scalar\ScalarEnumType;
use Doctrineum\Tests\Scalar\Helpers\WithToStringTestObject;
use Doctrineum\Tests\Scalar\ScalarEnumTypeTest;
use DrdPlus\Codes\Code;
use DrdPlus\Codes\EnumTypes\AbstractCodeType;
use DrdPlus\Codes\Partials\AbstractCode;
use Granam\Scalar\ScalarInterface;

abstract class AbstractCodeTypeTest extends ScalarEnumTypeTest
{

	/**
	 * @throws \ReflectionException
	 */
	protected function setUp(): void
	{
		parent::setUp();
		// remove all types from registration
		$_typesMap = new \ReflectionProperty(Type::class, '_typesMap');
		$_typesMap->setAccessible(true);
		$_typesMap->setValue([]);

		// remove any subtypes from registration
		$subTypeEnums = new \ReflectionProperty(ScalarEnumType::class, 'enumSubTypesMap');
		$subTypeEnums->setAccessible(true);
		$subTypeEnums->setValue([]);
	}

	/**
	 * @return AbstractCode|string
	 */
	protected function getRegisteredClass(): string
	{
		$registeredClass = \preg_replace('~(?:\\\EnumTypes)?(\\\[[:alpha:]]+)Type$~', '$1', $this->getTypeClass());
		self::assertTrue(
			\is_a($registeredClass, Code::class, true),
			"Estimated registered enum class {$registeredClass} should be child of " . Code::class
		);

		return $registeredClass;
	}

	public function provideValuesFromDb(): array
	{
		/** @var AbstractCode $registeredClass */
		$registeredClass = $this->getRegisteredClass();

		return \array_map(
			function (string $value) use ($registeredClass) {
				return [$registeredClass . '::' . $value];
			},
			$registeredClass::getPossibleValues()
		);
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
		try {
			$enum = $this->createSut()->convertToPHPValue($valueFromDb, $platform);
		} catch (EnumClassNotFound $enumClassNotFound) {
			self::fail(
				'Seems code is not registered as a sub-type in \DrdPlus\Codes\EnumTypes\CodeType::registerSelf, '
				. $enumClassNotFound->getMessage() . '; ' . $enumClassNotFound->getTraceAsString()
			);
		}
		if ($valueFromDb === null) {
			self::assertNull($enum);
		} else {
			self::assertInstanceOf($this->getRegisteredClass(), $enum);
			self::assertSame($this->parseEnumValueFromDatabaseValue($valueFromDb), $enum->getValue());
		}
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
			self::assertSame($this->parseEnumValueFromDatabaseValue($valueFromDb), $enum->getValue());
		}
		$scalaEnumTypeClass::removeSubTypeEnum($this->getSubTypeEnumClass());
	}

	protected function parseEnumValueFromDatabaseValue(string $valueFromDb): string
	{
		$parts = \explode('::', $valueFromDb);
		self::assertCount(2, $parts, "Unexpected value from database '$valueFromDb'");

		return $parts[1];
	}

	/**
	 * @test
	 * @throws \Doctrine\DBAL\DBALException
	 * @throws \ReflectionException
	 */
	public function I_can_register_all_codes_at_once(): void
	{
		$typeName = $this->getExpectedTypeName();
		self::assertFalse(Type::hasType($typeName), "Type of name '{$typeName}' should not be registered yet");
		/** @var AbstractCodeType $typeClass */
		$typeClass = $this->getTypeClass();
		foreach ($this->getRelatedCodeClasses() as $relatedCodeClass) {
			self::assertFalse(
				$typeClass::hasSubTypeEnum($relatedCodeClass),
				"Sub-type enum of a class '{$relatedCodeClass}' should not be registered yet"
			);
		}

		$typeClass::registerSelf();
		self::assertTrue(
			Type::hasType($typeName),
			"Type of name '{$typeName}' is not registered. Have you used expected '_code' suffix ?"
		);

		$testedType = Type::getType($typeName);
		$platform = $this->createPlatform();
		foreach ($this->getRelatedCodeClasses() as $relatedCodeClass) {
			self::assertTrue(
				$typeClass::hasSubTypeEnum($relatedCodeClass),
				"Sub-type enum of a class '{$relatedCodeClass}' is not registered"
			);
			foreach ($relatedCodeClass::getPossibleValues() as $possibleValue) {
				$asPhp = $testedType->convertToPHPValue($relatedCodeClass . '::' . $possibleValue, $platform);
				self::assertInstanceOf($relatedCodeClass, $asPhp);
				/** @var AbstractCode $asPhp */
				self::assertSame($possibleValue, $asPhp->getValue());
			}
		}
		$typeClass::registerSelf(); // tests if can call registering repeatedly
	}

	/**
	 * @return \Mockery\MockInterface|AbstractPlatform
	 */
	protected function createPlatform()
	{
		return $this->mockery(AbstractPlatform::class);
	}

	/**
	 * @return array|AbstractCode[]
	 * @throws \ReflectionException
	 */
	protected function getRelatedCodeClasses(): array
	{
		$relatedRootCodeClass = $this->getRegisteredClass();
		$codeReflection = new \ReflectionClass(Code::class);
		$rootDir = \dirname($codeReflection->getFileName());

		$concreteClassesFromDir = $this->getConcreteClassesFromDir($rootDir, $codeReflection->getNamespaceName());
		$relatedCodeClasses = [];
		foreach ($concreteClassesFromDir as $class) {
			if (\is_a($class, $relatedRootCodeClass, true /* instance is not needed */)) {
				$relatedCodeClasses[] = $class;
			}
		}

		return $relatedCodeClasses;
	}

	/**
	 * @param string $rootDir
	 * @param string $rootNamespace
	 * @return array|string[]
	 * @throws \ReflectionException
	 */
	private function getConcreteClassesFromDir($rootDir, $rootNamespace): array
	{
		$concreteClasses = [];
		$directoryIterator = new \DirectoryIterator($rootDir);
		foreach ($directoryIterator as $folder) {
			if ($folder->isDot()) {
				continue;
			}
			if ($folder->isDir()) {
				$namespace = rtrim($rootNamespace, '\\') . '\\' . $folder->getBasename();
				foreach ($this->getConcreteClassesFromDir($folder->getPathname(), $namespace) as $concreteClass) {
					$concreteClasses[] = $concreteClass;
				}
			} else {
				$className = $rootNamespace . '\\' . $folder->getBasename('.php');
				if (class_exists($className) && !(new \ReflectionClass($className))->isAbstract()) {
					$concreteClasses[] = $className;
				}
			}
		}

		return $concreteClasses;
	}

	/**
	 * @test
	 * @expectedException \DrdPlus\Codes\EnumTypes\Exceptions\UnknownCodeClass
	 * @expectedExceptionMessageRegExp ~non-existing-class~
	 * @throws \ReflectionException
	 */
	public function I_can_not_register_non_existing_class(): void
	{
		$reflectionClass = new \ReflectionClass(self::getSutClass());
		$registerCodeAsSubtypeEnum = $reflectionClass->getMethod('registerCodeAsSubtypeEnum');
		$registerCodeAsSubtypeEnum->setAccessible(true);
		$registerCodeAsSubtypeEnum->invoke($reflectionClass->newInstanceWithoutConstructor(), 'non-existing-class');
	}

	/**
	 * @test
	 * @expectedException \DrdPlus\Codes\EnumTypes\Exceptions\ExpectedEnumClass
	 * @expectedExceptionMessageRegExp ~stdClass~
	 * @throws \ReflectionException
	 */
	public function I_can_not_register_non_enum_class(): void
	{
		$reflectionClass = new \ReflectionClass(self::getSutClass());
		$registerCodeAsSubtypeEnum = $reflectionClass->getMethod('registerCodeAsSubtypeEnum');
		$registerCodeAsSubtypeEnum->setAccessible(true);
		$registerCodeAsSubtypeEnum->invoke($reflectionClass->newInstanceWithoutConstructor(), \stdClass::class);
	}

	/**
	 * @test
	 */
	public function I_can_get_pure_integer_in_enum(): void
	{
		self::assertFalse(false, 'Codes are not supposed to hold numbers');
	}

	/**
	 * @test
	 */
	public function I_can_get_enum_with_pure_integer_zero(): void
	{
		self::assertFalse(false, 'Codes are not supposed to hold numbers');
	}

	/**
	 * @test
	 */
	public function I_can_get_enum_with_pure_float(): void
	{
		self::assertFalse(false, 'Codes are not supposed to hold numbers');
	}

	/**
	 * @test
	 */
	public function I_can_get_enum_with_pure_float_zero(): void
	{
		self::assertFalse(false, 'Codes are not supposed to hold numbers');
	}

	/**
	 * @test
	 */
	public function I_can_get_enum_with_pure_false(): void
	{
		self::assertFalse(false, 'Codes are not supposed to hold booleans');
	}

	/**
	 * @test
	 */
	public function I_can_get_enum_with_pure_true(): void
	{
		self::assertFalse(false, 'Codes are not supposed to hold booleans');
	}

	/**
	 * @param null $value
	 * @throws \ReflectionException
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function enum_as_database_value_is_string_value_of_that_enum($value = null): void
	{
		parent::enum_as_database_value_is_string_value_of_that_enum($this->getSomeValueFromDatabaseForEnum());
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	protected function getSomeValueFromDatabaseForEnum(): string
	{
		$codeClass = $this->getRegisteredClass();
		$enumValue = $this->getSomeEnumValue();

		return $codeClass . '::' . $enumValue; // value prefixed with source class full name
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	protected function getSomeEnumValue(): string
	{
		$codeClass = $this->getRegisteredClass();
		$reflectionClass = new \ReflectionClass($codeClass);
		$constants = $reflectionClass->getConstants();
		self::assertNotEmpty($constants, "Code {$codeClass} does not have any constants");

		return $constants[array_rand($constants, 1)];
	}

	/**
	 * @test
	 * @expectedException \DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode
	 * @expectedExceptionMessageRegExp ~''~
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function I_get_enum_with_empty_string_on_conversion(): void
	{
		parent::I_get_enum_with_empty_string_on_conversion();
	}

	/**
	 * @test
	 * @param ScalarInterface|null $toStringObject
	 * @throws \ReflectionException
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function object_with_to_string_to_php_value_is_enum_with_that_string(ScalarInterface $toStringObject = null): void
	{
		$platform = $this->getPlatform();
		$value = $toStringObject ? $toStringObject->__toString() : $this->getSomeValueFromDatabaseForEnum();
		$enum = $this->createSut()->convertToPHPValue($toStringObject ?? new WithToStringTestObject($value), $platform);
		self::assertInstanceOf($this->getRegisteredClass(), $enum);
		// its always persisted as a sub-type with value prefixed code class
		$expectedValue = $this->parseEnumValueFromDatabaseValue($value);
		self::assertSame($expectedValue, $enum->getValue());
		self::assertSame($expectedValue, (string) $enum);
	}

	/**
	 * @test
	 * @expectedException \DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode
	 * @expectedExceptionMessageRegExp ~without~
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function I_get_default_enum_class_if_subtype_regexp_does_not_match(): void
	{
		parent::I_get_default_enum_class_if_subtype_regexp_does_not_match();
	}

	/**
	 * @test
	 */
	public function I_can_use_subtype(): void
	{
		self::assertTrue(true, 'Of course I can, whole code philosophy is build on sub-types');
	}

	/**
	 * @test
	 * @throws \ReflectionException
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function Code_value_is_prefixed_by_its_class_name_for_database_persistence(): void
	{
		$platform = $this->getPlatform();
		$enumClass = $this->getRegisteredClass();
		$enumValue = $this->getSomeEnumValue();
		$enum = $enumClass::getIt($enumValue);
		$enumAsDatabaseValue = $this->createSut()->convertToDatabaseValue($enum, $platform);
		self::assertSame("$enumClass::$enumValue", $enumAsDatabaseValue);
		$reconstructedEnum = $this->createSut()->convertToPHPValue($enumAsDatabaseValue, $platform);
		self::assertSame($reconstructedEnum, $enum);
	}
}