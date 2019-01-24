<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Scalar;

use Granam\Scalar\ScalarInterface;
use Granam\Scalar\Tools\ToScalar;
use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

/**
 * Inspired by @link http://github.com/marc-mabe/php-enum
 */
class ScalarEnum extends StrictObject implements ScalarEnumInterface
{
    /** @var ScalarEnum[] */
    private static $createdEnums = [];
    /** @var string|int|float|bool */
    protected $enumValue;

    /**
     * @param bool|float|int|string|ScalarInterface $enumValue
     * @throws Exceptions\UnexpectedValueToEnum
     */
    protected function __construct($enumValue)
    {
        $this->enumValue = static::convertToEnumFinalValue($enumValue);
    }

    /**
     * @param bool|float|int|string|ScalarInterface $enumValue
     * @return string|float|int|bool
     * @throws \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     */
    protected static function convertToEnumFinalValue($enumValue)
    {
        try {
            return ToScalar::toScalar($enumValue, true /* strict */);
        } catch (\Granam\Scalar\Tools\Exceptions\WrongParameterType $exception) {
            throw new Exceptions\UnexpectedValueToEnum($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param bool|float|int|string|ScalarInterface $enumValue
     * @return ScalarEnumInterface
     * @throws \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     * @throws \Doctrineum\Scalar\Exceptions\CanNotCreateInstanceOfAbstractEnum
     */
    public static function getEnum($enumValue)
    {
        return static::getEnumFromNamespace($enumValue, static::getInnerNamespace());
    }

    /**
     * @param int|float|string|bool $enumValue
     * @param string $namespace
     * @return ScalarEnumInterface
     * @throws Exceptions\UnexpectedValueToEnum
     * @throws Exceptions\CanNotCreateInstanceOfAbstractEnum
     */
    protected static function getEnumFromNamespace($enumValue, string $namespace)
    {
        $finalEnumValue = static::convertToEnumFinalValue($enumValue);
        if (!static::hasCreatedEnum($finalEnumValue, $namespace)) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            static::addCreatedEnum(static::createEnum($finalEnumValue), $namespace);
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return static::getCreatedEnum($finalEnumValue, $namespace);
    }

    protected static function hasCreatedEnum($enumValue, string $namespace)
    {
        return isset(self::$createdEnums[self::createKey($namespace)][self::createKey($enumValue)]);
    }

    /**
     * @param string|int|float|bool $key
     * @return string
     */
    protected static function createKey($key): string
    {
        return \serialize($key);
    }

    /**
     * @param ScalarEnumInterface $enum
     * @param string $namespace
     * @throws \Doctrineum\Scalar\Exceptions\EnumIsAlreadyBuilt
     */
    protected static function addCreatedEnum(ScalarEnumInterface $enum, string $namespace)
    {
        $namespaceKey = self::createKey($namespace);
        $enumKey = self::createKey($enum->getValue());
        if (isset(self::$createdEnums[$namespaceKey][$enumKey])) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            throw new Exceptions\EnumIsAlreadyBuilt(
                'Enum of namespace key ' . \var_export($namespaceKey, true) . ' and enum key ' . \var_export($enumKey, true) .
                ' is already registered with enum of class ' . \get_class(static::getCreatedEnum($enum->getValue(), $namespace))
            );
        }

        if (!array_key_exists($namespaceKey, self::$createdEnums)) {
            self::$createdEnums[$namespaceKey] = [];
        }

        self::$createdEnums[$namespaceKey][$enumKey] = $enum;
    }

    /**
     * @param mixed $enumValue
     * @param string $namespace
     * @return ScalarEnumInterface
     * @throws Exceptions\EnumIsNotBuilt
     */
    protected static function getCreatedEnum($enumValue, string $namespace)
    {
        $namespaceKey = self::createKey($namespace);
        $enumKey = self::createKey($enumValue);
        if (!isset(self::$createdEnums[$namespaceKey][$enumKey])) {
            throw new Exceptions\EnumIsNotBuilt(
                'Enum of namespace key ' . \var_export($namespaceKey, true) . ' and enum key ' . \var_export($enumKey, true) . ' is not registered'
            );
        }

        return self::$createdEnums[self::createKey($namespace)][self::createKey($enumValue)];
    }

    /**
     * @param string|int|float|bool $finalEnumValue
     * @return ScalarEnum
     * @throws \Doctrineum\Scalar\Exceptions\CanNotCreateInstanceOfAbstractEnum
     * @throws \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     */
    protected static function createEnum($finalEnumValue)
    {
        if (!\is_scalar($finalEnumValue)) {
            throw new Exceptions\UnexpectedValueToEnum('Expected scalar, got ' . \gettype($finalEnumValue));
        }
        /** @noinspection PhpUnhandledExceptionInspection */
        $reflection = new \ReflectionClass(static::class);
        if ($reflection->isAbstract()) {
            throw new Exceptions\CanNotCreateInstanceOfAbstractEnum(
                'Can not create instance of abstract enum ' . static::class
                . ' (with value ' . ValueDescriber::describe($finalEnumValue) . ').'
                . ' Have you forget to register a descendant or a sub-type?'
            );
        }

        return new static($finalEnumValue);
    }

    /**
     * @return string
     */
    protected static function getInnerNamespace(): string
    {
        return static::class;
    }

    /**
     * @return string
     * @see getValue()
     */
    public function __toString() // do NOT use : string type hint as it is very complicated for tests with mocks to satisfy
    {
        return (string)$this->getValue();
    }

    /**
     * @return string|int|float|bool
     */
    public function getValue()
    {
        return $this->enumValue;
    }

    /**
     * Doctrineum enums are intentionally not final, but should not be compared by just a value.
     * Use $enum1 === $enum2 find out same instances or $enum1->is($enum2) for equality of different instances.
     * Think twice before suppressing $sameClassOnly condition, because ArticleTypeEnum->getValue == RoleEnum->getValue
     * is true.
     *
     * @param ScalarEnumInterface $enum
     * @param bool $sameClassOnly = false
     * @return bool
     */
    public function is(ScalarEnumInterface $enum, bool $sameClassOnly = true): bool
    {
        return $this->getValue() === $enum->getValue()
            && (!$sameClassOnly || static::class === \get_class($enum));
    }

    /**
     * @throws \Doctrineum\Scalar\Exceptions\CanNotBeCloned
     */
    public function __clone()
    {
        throw new Exceptions\CanNotBeCloned('Enum as a singleton can not be cloned. Use same instance everywhere.');
    }
}