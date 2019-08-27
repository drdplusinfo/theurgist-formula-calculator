<?php declare(strict_types=1);

namespace DrdPlus\Codes\Armaments;

/**
 * @method static SlingStoneCode getIt($codeValue)
 * @method static SlingStoneCode findIt($codeValue)
 */
class SlingStoneCode extends ProjectileCode
{
    public const SLING_STONE_LIGHT = 'sling_stone_light';
    public const SLING_STONE_HEAVIER = 'sling_stone_heavier';

    /**
     * @return array|string[]
     */
    protected static function getDefaultValues(): array
    {
        return [
            self::SLING_STONE_LIGHT,
            self::SLING_STONE_HEAVIER,
        ];
    }

    /**
     * @param string $newSlingStoneCode
     * @param array $translations
     * @return bool
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidLanguageCode
     * @throws \DrdPlus\Codes\Partials\Exceptions\UnknownTranslationPlural
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidTranslationFormat
     */
    public static function addNewSlingStoneCode(string $newSlingStoneCode, array $translations): bool
    {
        return static::addNewCode($newSlingStoneCode, $translations);
    }

    /**
     * @return bool
     */
    final public function isSlingStone(): bool
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
    public function isDart(): bool
    {
        return false;
    }

    protected function getTranslationsFileName(): string
    {
        return __DIR__ . '/translations/sling_stone.csv';
    }

}