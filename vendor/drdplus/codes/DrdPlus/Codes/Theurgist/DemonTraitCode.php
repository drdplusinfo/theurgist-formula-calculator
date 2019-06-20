<?php declare(strict_types=1);

namespace DrdPlus\Codes\Theurgist;

/**
 * @method static DemonTraitCode getIt($codeValue)
 * @method static DemonTraitCode findIt($codeValue)
 */
class DemonTraitCode extends AbstractTheurgistCode
{
    public const UNLIMITED_ENDURANCE = 'unlimited_endurance';
    public const CHEAP_UNLIMITED_CAPACITY = 'cheap_unlimited_capacity';
    public const UNLIMITED_CAPACITY = 'unlimited_capacity';
    public const CASTER = 'caster';
    public const FORMULER = 'formuler';
    public const BUILDER = 'builder';

    public static function getPossibleValues(): array
    {
        return [
            self::UNLIMITED_ENDURANCE,
            self::CHEAP_UNLIMITED_CAPACITY,
            self::UNLIMITED_CAPACITY,
            self::CASTER,
            self::FORMULER,
            self::BUILDER,
        ];
    }

    protected function fetchTranslations(): array
    {
        return [
            'cs' => [
                'one' => [
                    self::UNLIMITED_ENDURANCE => 'neomezená výdrž',
                    self::CHEAP_UNLIMITED_CAPACITY => 'neomezená kapacita',
                    self::UNLIMITED_CAPACITY => 'neomezená kapacita',
                    self::CASTER => 'sesilatel',
                    self::FORMULER => 'formulovač',
                    self::BUILDER => 'budovatel',
                ],
            ],
        ];
    }

}