<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Integer;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrineum\Scalar\ScalarEnumInterface;
use Doctrineum\Scalar\ScalarEnumType;
use Granam\Integer\Tools\ToInteger;

/**
 * Class EnumType
 *
 * @package Doctrineum
 * @method static IntegerEnumType getType($name),
 * @see Type::getType
 */
class IntegerEnumType extends ScalarEnumType
{
    public const INTEGER_ENUM = 'integer_enum';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::INTEGER_ENUM;
    }

    /**
     * @see \Doctrineum\Scalar\EnumType::convertToPHPValue for usage
     * @param mixed $enumValue
     * @return IntegerEnum
     * @throws \Doctrineum\Integer\Exceptions\UnexpectedValueToConvert
     */
    protected function convertToEnum($enumValue): ScalarEnumInterface
    {
        return parent::convertToEnum($this->toInteger($enumValue));
    }

    /**
     * @param $value
     * @return int
     * @throws \Doctrineum\Integer\Exceptions\UnexpectedValueToConvert
     */
    protected function toInteger($value): int
    {
        try {
            return ToInteger::toInteger($value, true /* strict */);
        } catch (\Granam\Integer\Tools\Exceptions\WrongParameterType $exception) {
            // wrapping exception by a local one
            throw new Exceptions\UnexpectedValueToConvert($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     * @return string
     */
    public function getSQLDeclaration(
        /** @noinspection PhpUnusedParameterInspection */
        array $fieldDeclaration,
        AbstractPlatform $platform
    ): string
    {
        return 'INTEGER';
    }

    /**
     * Just for your information, is not used at code.
     * Maximum length of default SQL integer, @link http://en.wikipedia.org/wiki/Integer_%28computer_science%29
     *
     * @param AbstractPlatform $platform
     * @return int
     */
    public function getDefaultLength(
        /** @noinspection PhpUnusedParameterInspection */
        AbstractPlatform $platform
    ): int
    {
        return 10;
    }
}