<?php declare(strict_types=1);

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
    public const OWN = 'own';
    public const CORPSE = 'corpse';
    public const DUMMY = 'dummy';
    public const GLASSES = 'glasses';
    public const WEAPON = 'weapon';
    public const BOTTLE = 'bottle';
    public const FLACON = 'flacon';
    public const AMULET = 'amulet';
    public const ARMAMENT = 'armament';
    public const MUSIC_INSTRUMENT = 'music_instrument';
    public const ROUGE = 'rouge';

    public static function getPossibleValues(): array
    {
        return [
            self::CLOCK,
            self::PEBBLE,
            self::WAND_OR_RING,
            self::OWN,
            self::CORPSE,
            self::DUMMY,
            self::GLASSES,
            self::WEAPON,
            self::BOTTLE,
            self::FLACON,
            self::AMULET,
            self::ARMAMENT,
            self::MUSIC_INSTRUMENT,
            self::ROUGE,
        ];
    }

    protected static function getDefaultValue(): string
    {
        return self::CLOCK;
    }

    /**
     * @return array|string[]
     */
    protected function fetchTranslations(): array
    {
        return [
            'cs' => [
                'one' => [
                    self::CLOCK => 'hodiny',
                    self::PEBBLE => 'kamínek, oblázek',
                    self::WAND_OR_RING => 'hůl, hůlka, prsten',
                    self::OWN => 'vlastní',
                    self::CORPSE => 'mrtvola',
                    self::DUMMY => 'panák, pokud možno hliněný',
                    self::GLASSES => 'brýle, monokl, kukátko',
                    self::WEAPON => 'zbraň',
                    self::BOTTLE => 'láhev, korbel, krýgl či hrnek',
                    self::FLACON => 'flakón',
                    self::AMULET => 'amulet',
                    self::ARMAMENT => 'zbroj, štít, zbraň',
                    self::MUSIC_INSTRUMENT => 'hudební nástroj',
                    self::ROUGE => 'tulák, lovec',
                ],
            ],
        ];
    }

}