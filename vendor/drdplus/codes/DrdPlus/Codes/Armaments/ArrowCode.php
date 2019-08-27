<?php declare(strict_types=1); 

namespace DrdPlus\Codes\Armaments;

/**
 * @method static ArrowCode getIt($codeValue)
 * @method static ArrowCode findIt($codeValue)
 */
class ArrowCode extends ProjectileCode
{
    public const BASIC_ARROW = 'basic_arrow';
    public const LONG_RANGE_ARROW = 'long_range_arrow';
    public const WAR_ARROW = 'war_arrow';
    public const PIERCING_ARROW = 'piercing_arrow';
    public const HOLLOW_ARROW = 'hollow_arrow';
    public const CRIPPLING_ARROW = 'crippling_arrow';
    public const INCENDIARY_ARROW = 'incendiary_arrow';
    public const SILVER_ARROW = 'silver_arrow';

    /**
     * @return array|string[]
     */
    protected static function getDefaultValues(): array
    {
        return [
            self::BASIC_ARROW,
            self::LONG_RANGE_ARROW,
            self::WAR_ARROW,
            self::PIERCING_ARROW,
            self::HOLLOW_ARROW,
            self::CRIPPLING_ARROW,
            self::INCENDIARY_ARROW,
            self::SILVER_ARROW,
        ];
    }

    /**
     * @param string $arrowCodeValue
     * @param array $translations
     * @return bool
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidLanguageCode
     * @throws \DrdPlus\Codes\Partials\Exceptions\UnknownTranslationPlural
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidTranslationFormat
     */
    public static function addNewArrowCode(string $arrowCodeValue, array $translations): bool
    {
        return static::addNewCode($arrowCodeValue, $translations);
    }

    /**
     * @return bool
     */
    final public function isArrow(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isDart(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isSlingStone(): bool
    {
        return false;
    }

    protected function getTranslationsFileName(): string
    {
        return __DIR__ . '/translations/arrow_code.csv';
    }
}