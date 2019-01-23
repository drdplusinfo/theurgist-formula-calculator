<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Armaments;

/**
 * @method static BodyArmorCode getIt($codeValue)
 * @method static BodyArmorCode findIt($codeValue)
 */
class BodyArmorCode extends ArmorCode
{
    public const WITHOUT_ARMOR = 'without_armor';
    public const PADDED_ARMOR = 'padded_armor';
    public const LEATHER_ARMOR = 'leather_armor';
    public const HOBNAILED_ARMOR = 'hobnailed_armor';
    public const CHAINMAIL_ARMOR = 'chainmail_armor';
    public const SCALE_ARMOR = 'scale_armor';
    public const PLATE_ARMOR = 'plate_armor';
    public const FULL_PLATE_ARMOR = 'full_plate_armor';

    /**
     * @return array|string[]
     */
    protected static function getDefaultValues(): array
    {
        return [
            self::WITHOUT_ARMOR,
            self::PADDED_ARMOR,
            self::LEATHER_ARMOR,
            self::HOBNAILED_ARMOR,
            self::CHAINMAIL_ARMOR,
            self::SCALE_ARMOR,
            self::PLATE_ARMOR,
            self::FULL_PLATE_ARMOR,
        ];
    }

    /**
     * @param string $bodyArmorCodeValue
     * @param array $translations
     * @return bool
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidLanguageCode
     * @throws \DrdPlus\Codes\Partials\Exceptions\UnknownTranslationPlural
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidTranslationFormat
     */
    public static function addNewBodyArmorCode(string $bodyArmorCodeValue, array $translations): bool
    {
        return static::addNewCode($bodyArmorCodeValue, $translations);
    }

    /**
     * @return bool
     */
    public function isHelm(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    final public function isBodyArmor(): bool
    {
        return true;
    }

    protected function getTranslationsFileName(): string
    {
        return __DIR__ . '/translations/body_armor.csv';
    }

}