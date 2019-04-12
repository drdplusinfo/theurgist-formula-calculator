<?php
namespace DrdPlus\Codes\Theurgist;

/**
 * @method static DemonTraitCode getIt($codeValue)
 * @method static DemonTraitCode findIt($codeValue)
 */
class DemonTraitCode extends AbstractTheurgistCode
{
    public const CHEAP_UNLIMITED_CAPACITY = 'cheap_unlimited_capacity';
    public const UNLIMITED_CAPACITY = 'unlimited_capacity';
    public const CASTER = 'caster';
    public const FORMULER = 'formuler';
    public const BUILDER = 'builder';

    public static function getPossibleValues(): array
    {
        return [
            self::CHEAP_UNLIMITED_CAPACITY,
            self::UNLIMITED_CAPACITY,
            self::CASTER,
            self::FORMULER,
            self::BUILDER,
        ];
    }

    private static $translations = [
        'cs' => [
            self::CHEAP_UNLIMITED_CAPACITY => 'neomezená kapacita',
            self::UNLIMITED_CAPACITY => 'neomezená kapacita',
            self::CASTER => 'sesilatel',
            self::FORMULER => 'formulovač',
            self::BUILDER => 'budovatel',
        ],
    ];

    protected function getTranslations(string $languageCode): array
    {
        return self::$translations[$languageCode] ?? [];
    }

}