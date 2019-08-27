<?php declare(strict_types=1); 

namespace DrdPlus\Codes\Armaments;

/**
 * @method static DartCode getIt($codeValue)
 * @method static DartCode findIt($codeValue)
 */
class DartCode extends ProjectileCode
{
    public const BASIC_DART = 'basic_dart';
    public const WAR_DART = 'war_dart';
    public const PIERCING_DART = 'piercing_dart';
    public const HOLLOW_DART = 'hollow_dart';
    public const SILVER_DART = 'silver_dart';

    /**
     * @return array|string[]
     */
    protected static function getDefaultValues(): array
    {
        return [
            self::BASIC_DART,
            self::WAR_DART,
            self::PIERCING_DART,
            self::HOLLOW_DART,
            self::SILVER_DART,
        ];
    }

    /**
     * @param string $dartCodeValue
     * @param array $translations
     * @return bool
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidLanguageCode
     * @throws \DrdPlus\Codes\Partials\Exceptions\UnknownTranslationPlural
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidTranslationFormat
     */
    public static function addNewDartCode(string $dartCodeValue, array $translations): bool
    {
        return static::addNewCode($dartCodeValue, $translations);
    }

    /**
     * @return bool
     */
    final public function isDart(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isArrow(): bool
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
        return __DIR__ . '/translations/dart_code.csv';
    }

}