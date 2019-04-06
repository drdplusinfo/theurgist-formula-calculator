<?php
declare(strict_types=1);

namespace DrdPlus\Codes\Partials;

use DrdPlus\Codes\Code;
use Granam\Scalar\Tools\ToString;
use Granam\ScalarEnum\ScalarEnum;
use Granam\ScalarEnum\ScalarEnumInterface;
use Granam\String\StringInterface;
use Granam\Tools\ValueDescriber;

abstract class AbstractCode extends ScalarEnum implements Code
{

    protected static $possibleValues;

    /**
     * Overload this by basic array with listed constants. Taking them via Reflection is not the fastest way.
     *
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        if ((static::$possibleValues[static::class] ?? null) === null) {
            static::$possibleValues[static::class] = [];
            try {
                $reflectionClass = new \ReflectionClass(static::class);
            } catch (\ReflectionException $reflectionException) {
                throw new Exceptions\CanNotDeterminePossibleValuesFromClassReflection($reflectionException->getMessage());
            }
            foreach ($reflectionClass->getReflectionConstants() as $reflectionConstant) {
                if ($reflectionConstant->isPublic()) {
                    static::$possibleValues[static::class][] = $reflectionConstant->getValue();
                }
            }
        }

        return static::$possibleValues[static::class];
    }

    /**
     * @param string|StringInterface $codeValue
     * @return AbstractCode|ScalarEnumInterface
     * @throws \Granam\ScalarEnum\Exceptions\WrongValueForScalarEnum
     * @throws \DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode
     */
    public static function getIt($codeValue): AbstractCode
    {
        return self::getEnum($codeValue);
    }

    /**
     * @param string|StringInterface $codeValue
     * @return AbstractCode|ScalarEnumInterface
     * @throws \Granam\ScalarEnum\Exceptions\WrongValueForScalarEnum
     * @throws \DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode
     */
    public static function findIt($codeValue): AbstractCode
    {
        if (static::hasIt($codeValue)) {
            return self::getIt($codeValue);
        }

        return self::getIt(static::getDefaultValue());
    }

    protected static function getDefaultValue(): string
    {
        $possibleValues = static::getPossibleValues();

        return \reset($possibleValues);
    }

    /**
     * @param string|StringInterface $codeValue
     * @return bool
     * @throws \Granam\ScalarEnum\Exceptions\WrongValueForScalarEnum
     */
    public static function hasIt($codeValue): bool
    {
        if ($codeValue === null) {
            return false;
        }

        return \in_array(ToString::toString($codeValue), self::getPossibleValues(), true);
    }

    /**
     * @param string|Code $codeValue
     * @throws \Granam\ScalarEnum\Exceptions\WrongValueForScalarEnum
     * @throws \DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode
     */
    protected function __construct($codeValue)
    {
        parent::__construct($codeValue);
        $this->guardCodeExistence($this->enumValue);
    }

    /**
     * @param string $codeValue
     * @throws \DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode
     */
    private function guardCodeExistence(string $codeValue)
    {
        if (!\in_array($codeValue, static::getPossibleValues(), true)) {
            throw new Exceptions\UnknownValueForCode('Given code value '
                . ValueDescriber::describe($codeValue)
                . ' is not known to ' . static::class
            );
        }
    }

    public function getValue(): string
    {
        return parent::getValue();
    }

}