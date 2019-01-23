<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Armaments\EnumTypes;

use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\EnumTypes\AbstractCodeType;
use Granam\Tools\ValueDescriber;

class WeaponlikeCodeType extends AbstractCodeType
{
    public const WEAPONLIKE_CODE = 'weaponlike_code';

    public static function registerSelf(): bool
    {
        $somethingRegistered = parent::registerSelf();

        $somethingRegistered = static::registerCodeAsSubTypeEnum(MeleeWeaponCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(RangedWeaponCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ShieldCode::class) || $somethingRegistered;

        return $somethingRegistered;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::WEAPONLIKE_CODE;
    }

    /**
     * @param bool|float|int|string $enumValue
     * @return string
     * @throws \DrdPlus\Codes\Armaments\EnumTypes\Exceptions\ThereIsNoDefaultEnumForWeaponlikeCode
     */
    protected static function getDefaultEnumClass($enumValue): string
    {
        throw new Exceptions\ThereIsNoDefaultEnumForWeaponlikeCode(
            'Given code value ' . ValueDescriber::describe($enumValue)
            . ' do not match to any value from any of registered subtypes: '
            . MeleeWeaponCode::class . ', ' . RangedWeaponCode::class . ' and ' . ShieldCode::class
        );
    }

}