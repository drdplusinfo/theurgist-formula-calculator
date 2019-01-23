<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Scalar;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrineum\SelfRegisteringType\AbstractSelfRegisteringType;
use Granam\Scalar\Tools\ToScalar;
use Granam\Tools\ValueDescriber;

/**
 * @method static ScalarEnumType getType($name),
 */
class ScalarEnumType extends AbstractSelfRegisteringType
{

    public const SCALAR_ENUM = 'scalar_enum';

    /** @var string[][] */
    private static $enumSubTypesMap = [];

    /**
     * You can register a class just once.
     *
     * @param string $subTypeEnumClass
     * @param string $subTypeEnumValueRegexp
     * @return bool
     * @throws \Doctrineum\Scalar\Exceptions\SubTypeEnumIsAlreadyRegistered
     * @throws \Doctrineum\Scalar\Exceptions\SubTypeEnumClassNotFound
     * @throws \Doctrineum\Scalar\Exceptions\SubTypeEnumHasToBeEnum
     * @throws \Doctrineum\Scalar\Exceptions\InvalidRegexpFormat
     */
    public static function registerSubTypeEnum(string $subTypeEnumClass, string $subTypeEnumValueRegexp): bool
    {
        if (!static::hasSubTypeEnum($subTypeEnumClass, $subTypeEnumValueRegexp)) {
            // registering same subtype enum class but with different regexp cause exception in following method
            return static::addSubTypeEnum($subTypeEnumClass, $subTypeEnumValueRegexp);
        }

        return false;
    }

    /**
     * @param $subTypeClassName
     * @param string|null $subTypeEnumValueRegexp
     * @return bool
     * @throws \Doctrineum\Scalar\Exceptions\InvalidRegexpFormat
     */
    public static function hasSubTypeEnum(string $subTypeClassName, string $subTypeEnumValueRegexp = null): bool
    {
        return
            (self::$enumSubTypesMap[static::getSubTypeEnumInnerNamespace()][$subTypeClassName] ?? null) !== null
            && (
                $subTypeEnumValueRegexp === null
                || (self::guardRegexpValid($subTypeEnumValueRegexp)
                    && self::$enumSubTypesMap[static::getSubTypeEnumInnerNamespace()][$subTypeClassName] === $subTypeEnumValueRegexp
                )
            );
    }

    /**
     * @return string
     */
    protected static function getSubTypeEnumInnerNamespace(): string
    {
        return static::class;
    }

    /**
     * Warning: Behave of registering more classes on same regexp (or simply matching same string) is undefined.
     *
     * @param string $subTypeEnumClass
     * @param string $subTypeEnumValueRegexp
     * @return bool
     * @throws \Doctrineum\Scalar\Exceptions\SubTypeEnumIsAlreadyRegistered
     * @throws \Doctrineum\Scalar\Exceptions\SubTypeEnumClassNotFound
     * @throws \Doctrineum\Scalar\Exceptions\SubTypeEnumHasToBeEnum
     * @throws \Doctrineum\Scalar\Exceptions\InvalidRegexpFormat
     */
    public static function addSubTypeEnum(string $subTypeEnumClass, string $subTypeEnumValueRegexp): bool
    {
        if (static::hasSubTypeEnum($subTypeEnumClass)) {
            throw new Exceptions\SubTypeEnumIsAlreadyRegistered(
                'SubType enum ' . ValueDescriber::describe($subTypeEnumClass) . ' is already registered with regexp '
                . self::$enumSubTypesMap[static::getSubTypeEnumInnerNamespace()][$subTypeEnumClass]
                . ' (requested to register with regexp ' . ValueDescriber::describe($subTypeEnumValueRegexp) . ')'
            );
        }
        /** The class has to be self-registering to by-pass enum and enum type bindings, @see ScalarEnum::createEnum */
        static::checkIfKnownEnum($subTypeEnumClass);
        static::guardRegexpValid($subTypeEnumValueRegexp);
        self::$enumSubTypesMap[static::getSubTypeEnumInnerNamespace()][$subTypeEnumClass] = $subTypeEnumValueRegexp;

        return true;
    }

    /**
     * @param string $subTypeClassName
     * @throws \Doctrineum\Scalar\Exceptions\SubTypeEnumClassNotFound
     * @throws \Doctrineum\Scalar\Exceptions\SubTypeEnumHasToBeEnum
     * @throws \Doctrineum\Scalar\Exceptions\SubTypeEnumHasToHaveFactoryMethod
     */
    protected static function checkIfKnownEnum(string $subTypeClassName): void
    {
        if (!\class_exists($subTypeClassName)) {
            throw new Exceptions\SubTypeEnumClassNotFound(
                'Sub-type class ' . ValueDescriber::describe($subTypeClassName) . ' has not been found'
            );
        }
        if (!\is_a($subTypeClassName, ScalarEnumInterface::class, true)) {
            throw new Exceptions\SubTypeEnumHasToBeEnum(
                'Sub-type class ' . ValueDescriber::describe($subTypeClassName) . ' has to be child of ' . ScalarEnumInterface::class
            );
        }
        if (!\method_exists($subTypeClassName, 'getEnum')) {
            throw new Exceptions\SubTypeEnumHasToHaveFactoryMethod(
                'Sub-type class ' . ValueDescriber::describe($subTypeClassName) . ' has to have public static method getEnum($enumValue)'
            );
        }
    }

    /**
     * @param string $regexp
     * @return bool
     * @throws \Doctrineum\Scalar\Exceptions\InvalidRegexpFormat
     */
    private static function guardRegexpValid(string $regexp): bool
    {
        if (!\preg_match('~^(.).*\1$~', $regexp)) {
            // the regexp does not start and end with same characters
            throw new Exceptions\InvalidRegexpFormat(
                'The given regexp is not enclosed by same delimiters and therefore is not valid: '
                . ValueDescriber::describe($regexp)
            );
        }

        return true;
    }

    /**
     * @param string $subTypeEnumClass
     * @return bool
     * @throws \Doctrineum\Scalar\Exceptions\SubTypeEnumIsNotRegistered
     */
    public static function removeSubTypeEnum(string $subTypeEnumClass): bool
    {
        if (!static::hasSubTypeEnum($subTypeEnumClass)) {
            throw new Exceptions\SubTypeEnumIsNotRegistered(
                'Sub-type ' . ValueDescriber::describe($subTypeEnumClass) . ' is not registered'
            );
        }
        unset(self::$enumSubTypesMap[static::getSubTypeEnumInnerNamespace()][$subTypeEnumClass]);

        return true;
    }

    /**
     * Gets the strongly recommended name of this type.
     * Its used at @see \Doctrine\DBAL\Platforms\AbstractPlatform::getDoctrineTypeComment
     * Note: also PhpStorm use it for click-through via @Column(type="foo-bar") notation,
     * if and only if is the value a constant (direct return of a string or constant).
     *
     * @return string
     */
    public function getName(): string
    {
        return self::SCALAR_ENUM;
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'VARCHAR(' . $this->getDefaultLength($platform) . ')';
    }

    /**
     * @param AbstractPlatform $platform
     * @return int
     */
    public function getDefaultLength(AbstractPlatform $platform): int
    {
        return 64;
    }

    /**
     * Convert enum instance to database string (or null) value
     *
     * @param ScalarEnumInterface $value
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @throws Exceptions\UnexpectedValueToDatabaseValue
     * @return string|int|float|bool|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        if (!\is_object($value) || !\is_a($value, ScalarEnumInterface::class)) {
            throw new Exceptions\UnexpectedValueToDatabaseValue(
                'Expected NULL or instance of ' . ScalarEnumInterface::class . ', got ' . ValueDescriber::describe($value)
            );
        }

        return $value->getValue();
    }

    /**
     * Convert database string value to Enum instance
     * This does NOT cast non-string scalars into string (integers, floats etc).
     * Even null remains null in returned Enum.
     * (But saving the value into database and pulling it back probably will do the to-string conversion)
     *
     * @param string|int|float|bool|null $value
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return ScalarEnum|null
     * @throws \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     * @throws \Doctrineum\Scalar\Exceptions\CouldNotDetermineEnumClass
     * @throws \Doctrineum\Scalar\Exceptions\EnumClassNotFound
     * @throws \Doctrineum\Scalar\Exceptions\CanNotCreateInstanceOfAbstractEnum
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ScalarEnumInterface
    {
        return $value === null
            ? null
            : $this->convertToEnum($value);
    }

    /**
     * @param string|int|float|bool $enumValue
     * @return ScalarEnum|ScalarEnumInterface
     * @throws \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     * @throws \Doctrineum\Scalar\Exceptions\CouldNotDetermineEnumClass
     * @throws \Doctrineum\Scalar\Exceptions\EnumClassNotFound
     * @throws \Doctrineum\Scalar\Exceptions\CanNotCreateInstanceOfAbstractEnum
     */
    protected function convertToEnum($enumValue): ScalarEnumInterface
    {
        $enumValue = $this->sanitizeValueForEnumClass($enumValue);
        // class of main enum or its registered sub-type, according to enum type and current value
        $enumClass = static::getEnumClass($enumValue);
        $enumValue = $this->prepareValueForEnum($enumValue);

        try {
            return $enumClass::getEnum($enumValue);
        } catch (Exceptions\CanNotCreateInstanceOfAbstractEnum $canNotCreateInstanceOfAbstractEnum) {
            throw new Exceptions\CanNotCreateInstanceOfAbstractEnum(
                'Enum value ' . ValueDescriber::describe($enumValue) . ' is paired with enum class ' . $enumClass
                . ', but creating an enum by it causes: ' . $canNotCreateInstanceOfAbstractEnum->getMessage()
                . "\nRegistered sub-types are " . (self::$enumSubTypesMap ? \var_export(self::$enumSubTypesMap, true) : "'none'")
                . ' and default enum class for given value ' . ValueDescriber::describe($enumValue)
                . ' is ' . static::getDefaultEnumClass($enumValue)
            );
        }
    }

    /**
     * @param $valueForEnum
     * @return float|int|string|bool
     * @throws \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     */
    protected function sanitizeValueForEnumClass($valueForEnum)
    {
        try {
            return ToScalar::toScalar($valueForEnum, true /* strict, without null */);
        } catch (\Granam\Scalar\Tools\Exceptions\WrongParameterType $exception) {
            throw new Exceptions\UnexpectedValueToEnum(
                'Unexpected value to convert. Expected scalar or null, got '
                . ValueDescriber::describe($valueForEnum),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param float|int|string|bool $valueForEnum
     * @return float|int|string|bool
     */
    protected function prepareValueForEnum($valueForEnum)
    {
        return $valueForEnum; // nothing to change here - intentioned for overload
    }

    /**
     * @param int|float|string|bool $enumValue
     * @return string|ScalarEnum Enum class absolute name
     * @throws \Doctrineum\Scalar\Exceptions\CouldNotDetermineEnumClass
     * @throws \Doctrineum\Scalar\Exceptions\EnumClassNotFound
     */
    protected static function getEnumClass($enumValue): string
    {
        if (!\array_key_exists(static::getSubTypeEnumInnerNamespace(), self::$enumSubTypesMap)
            || \count(self::$enumSubTypesMap[static::getSubTypeEnumInnerNamespace()]) === 0
        ) {
            // no subtype is registered at all
            return static::getDefaultEnumClass($enumValue);
        }

        foreach (self::$enumSubTypesMap[static::getSubTypeEnumInnerNamespace()] as $subTypeEnumClass => $subTypeEnumValueRegexp) {
            if (\preg_match($subTypeEnumValueRegexp, (string)$enumValue)) {
                return $subTypeEnumClass;
            }
        }

        // no subtype matched
        return static::getDefaultEnumClass($enumValue);
    }

    /**
     * @param int|float|string|bool $enumValue
     * @return string
     * @throws \Doctrineum\Scalar\Exceptions\CouldNotDetermineEnumClass
     * @throws \Doctrineum\Scalar\Exceptions\EnumClassNotFound
     */
    protected static function getDefaultEnumClass($enumValue): string
    {
        $enumTypeClass = static::class;
        $enumInSameNamespace = \preg_replace('~Type$~', '', $enumTypeClass);
        if ($enumInSameNamespace === $enumTypeClass) {
            throw new Exceptions\CouldNotDetermineEnumClass('Enum class could not be parsed from enum type class ' . $enumTypeClass);
        }
        if (\class_exists($enumInSameNamespace)) {
            return $enumInSameNamespace;
        }

        $inParentNamespace = \preg_replace('~\\\(\w+)\\\(\w+)$~', '\\\$2', $enumInSameNamespace);
        if (\class_exists($inParentNamespace)) {
            return $inParentNamespace;
        }

        throw new Exceptions\EnumClassNotFound(
            'Default enum class not found for enum type ' . static::class
            . ' (potential sub-types have not matched enum value ' . ValueDescriber::describe($enumValue) . ')'
        );
    }

    /**
     * If this Doctrine Type maps to an already mapped database type,
     * reverse schema engineering can't take them apart. You need to mark
     * one of those types as commented, which will have Doctrine use an SQL
     * comment to type-hint the actual Doctrine Type.
     *
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return boolean
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}