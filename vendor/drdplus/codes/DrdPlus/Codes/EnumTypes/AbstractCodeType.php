<?php
declare(strict_types=1);

namespace DrdPlus\Codes\EnumTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrineum\Scalar\Exceptions\UnexpectedValueToDatabaseValue;
use Doctrineum\Scalar\ScalarEnum;
use Doctrineum\Scalar\ScalarEnumType;
use DrdPlus\Codes\Code;
use Granam\Scalar\Tools\ToString;
use Granam\String\StringInterface;
use Granam\Tools\ValueDescriber;

abstract class AbstractCodeType extends ScalarEnumType
{
    /**
     * @param string|StringInterface $codeClass
     * @return bool
     * @throws \DrdPlus\Codes\EnumTypes\Exceptions\UnknownCodeClass
     * @throws \DrdPlus\Codes\EnumTypes\Exceptions\ExpectedEnumClass
     */
    protected static function registerCodeAsSubTypeEnum($codeClass): bool
    {
        $sanitizedCodeClass = ToString::toString($codeClass);
        if (!\class_exists($sanitizedCodeClass)) {
            throw new Exceptions\UnknownCodeClass('Given code class has not been found: ' . ValueDescriber::describe($codeClass));
        }
        if (!\is_a($sanitizedCodeClass, ScalarEnum::class, true)) {
            throw new Exceptions\ExpectedEnumClass(
                'Given class is not an enum: ' . ValueDescriber::describe($codeClass)
            );
        }
        if (static::hasSubTypeEnum($sanitizedCodeClass)) {
            return false;
        }

        return static::addSubTypeEnum($sanitizedCodeClass, '~^' . \preg_quote($sanitizedCodeClass, '~') . '::\w+$~');
    }

    /**
     * Prefixes code value by its class for later identification on restoring from database.
     *
     * @param Code $code
     * @param AbstractPlatform $platform
     * @return null|string
     * @throws \Doctrineum\Scalar\Exceptions\UnexpectedValueToDatabaseValue
     */
    public function convertToDatabaseValue($code, AbstractPlatform $platform): ?string
    {
        if ($code === null) {
            return null;
        }
        if (!\is_object($code) || !\is_a($code, Code::class)) {
            throw new UnexpectedValueToDatabaseValue(
                'Expected NULL or instance of ' . Code::class . ', got ' . ValueDescriber::describe($code)
            );
        }

        return \get_class($code) . '::' . $code->getValue();
    }

    /**
     * @param string|int|float|bool $valueForEnum
     * @return float|int|null|string
     * @throws \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     */
    protected function prepareValueForEnum($valueForEnum): string
    {
        $valueForEnum = parent::prepareValueForEnum($valueForEnum);

        // like DrdPlus\Codes\Armaments\MeleeWeaponCode::light_axe = light_axe
        return \preg_replace('~^[^:]+::~', '', $valueForEnum);
    }

}