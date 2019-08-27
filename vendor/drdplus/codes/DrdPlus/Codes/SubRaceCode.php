<?php declare(strict_types=1); 

namespace DrdPlus\Codes;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static SubRaceCode getIt($codeValue)
 * @method static SubRaceCode findIt($codeValue)
 */
class SubRaceCode extends TranslatableCode
{
    // human
    public const COMMON = 'common';
    public const HIGHLANDER = 'highlander';

    // elf
    // + common
    public const GREEN = 'green';
    public const DARK = 'dark';

    // dwarf
    // + common
    public const WOOD = 'wood';
    public const MOUNTAIN = 'mountain';

    // hobbit
    // + common

    // kroll
    // + common
    public const WILD = 'wild';

    // orc
    // + common
    public const SKURUT = 'skurut';
    public const GOBLIN = 'goblin';

    /**
     * @return array|string[][]
     */
    public static function getRaceToSubRaceValues(): array
    {
        return [
            RaceCode::HUMAN => [
                self::COMMON,
                self::HIGHLANDER,
            ],
            RaceCode::ELF => [
                self::COMMON,
                self::GREEN,
                self::DARK,
            ],
            RaceCode::DWARF => [
                self::COMMON,
                self::WOOD,
                self::MOUNTAIN,
            ],
            RaceCode::HOBBIT => [
                self::COMMON,
            ],
            RaceCode::KROLL => [
                self::COMMON,
                self::WILD,
            ],
            RaceCode::ORC => [
                self::COMMON,
                self::SKURUT,
                self::GOBLIN,
            ],
        ];
    }

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::COMMON,
            self::HIGHLANDER,
            self::GREEN,
            self::DARK,
            self::WOOD,
            self::MOUNTAIN,
            self::WILD,
            self::SKURUT,
            self::GOBLIN,
        ];
    }

    /**
     * @param RaceCode $raceCode
     * @return SubRaceCode
     * @throws \DrdPlus\Codes\Exceptions\UnknownRaceCode
     */
    public static function getDefaultSubRaceFor(RaceCode $raceCode): SubRaceCode
    {
        $subRace = static::getRaceToSubRaceValues()[$raceCode->getValue()][0] ?? null;
        if ($subRace === null) {
            throw new Exceptions\UnknownRaceCode(
                "Given race code '{$raceCode}' does not match with known sub-races "
                . \var_export(static::getRaceToSubRaceValues(), true)
            );
        }

        return static::getIt($subRace);
    }

    /**
     * @param RaceCode $raceCode
     * @return bool
     */
    public function isRace(RaceCode $raceCode): bool
    {
        return \in_array($this->getValue(), static::getRaceToSubRaceValues()[$raceCode->getValue()] ?? [], true);
    }

    protected function fetchTranslations(): array
    {
        return [
            self::$CS => [
                self::COMMON => [self::$ONE => 'běžný'],
                self::HIGHLANDER => [self::$ONE => 'horal'],
                self::GREEN => [self::$ONE => 'zelený'],
                self::DARK => [self::$ONE => 'temný'],
                self::WOOD => [self::$ONE => 'lesní'],
                self::MOUNTAIN => [self::$ONE => 'horský'],
                self::WILD => [self::$ONE => 'divoký'],
                self::SKURUT => [self::$ONE => 'skurut'],
                self::GOBLIN => [self::$ONE => 'goblin'],
            ],
            self::$EN => [
                self::COMMON => [self::$ONE => 'common'],
                self::HIGHLANDER => [self::$ONE => 'highlander'],
                self::GREEN => [self::$ONE => 'green'],
                self::DARK => [self::$ONE => 'dark'],
                self::WOOD => [self::$ONE => 'wood'],
                self::MOUNTAIN => [self::$ONE => 'mountain'],
                self::WILD => [self::$ONE => 'wild'],
                self::SKURUT => [self::$ONE => 'skurut'],
                self::GOBLIN => [self::$ONE => 'goblin'],
            ]
        ];
    }

}