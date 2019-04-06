<?php
declare(strict_types=1);

namespace DrdPlus\Codes\Theurgist;

/**
 * @method static DemonBodyCode getIt($codeValue)
 * @method static DemonBodyCode findIt($codeValue)
 */
class DemonBodyCode extends AbstractTheurgistCode
{
    public const CLOCK = 'clock';
    public const PEBBLE = 'pebble';
    public const WAND_OR_RING = 'wand_or_ring';

    public static function getPossibleValues(): array
    {
        return [
            self::CLOCK,
            self::PEBBLE,
            self::WAND_OR_RING,
        ];
    }

    protected static function getDefaultValue(): string
    {
        return self::CLOCK;
    }

    private static $translations = [
        'cs' => [
            self::CLOCK => 'hodiny',
            self::PEBBLE => 'kamínek, oblázek',
            self::WAND_OR_RING => 'hůl, hůlka, prsten',
        ],
    ];

    /**
     * @param string $languageCode
     * @return array|string[]
     */
    protected function getTranslations(string $languageCode): array
    {
        return self::$translations[$languageCode] ?? [];
    }

}