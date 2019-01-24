<?php
declare(strict_types=1); 

namespace DrdPlus\Codes;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static RaceCode getIt($codeValue)
 * @method static RaceCode findIt($codeValue)
 */
class RaceCode extends TranslatableCode
{
    public const HUMAN = 'human';
    public const ELF = 'elf';
    public const DWARF = 'dwarf';
    public const HOBBIT = 'hobbit';
    public const KROLL = 'kroll';
    public const ORC = 'orc';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::HUMAN,
            self::ELF,
            self::DWARF,
            self::HOBBIT,
            self::KROLL,
            self::ORC,
        ];
    }

    /**
     * @return SubRaceCode
     * @throws \DrdPlus\Codes\Exceptions\UnknownRaceCode
     */
    public function getDefaultSubRaceCode(): SubRaceCode
    {
        return SubRaceCode::getDefaultSubRaceFor($this);
    }

    protected function fetchTranslations(): array
    {
        return [
            'cs' => [
                self::HUMAN => [self::$ONE => 'člověk'],
                self::ELF => [self::$ONE => 'elf'],
                self::DWARF => [self::$ONE => 'trpaslík'],
                self::HOBBIT => [self::$ONE => 'hobit'],
                self::KROLL => [self::$ONE => 'kroll'],
                self::ORC => [self::$ONE => 'ork'],
            ],
            'en' => [
                self::HUMAN => [self::$ONE => 'human'],
                self::ELF => [self::$ONE => 'elf'],
                self::DWARF => [self::$ONE => 'dwarf'],
                self::HOBBIT => [self::$ONE => 'hobbit'],
                self::KROLL => [self::$ONE => 'kroll'],
                self::ORC => [self::$ONE => 'orc'],
            ]
        ];
    }

}