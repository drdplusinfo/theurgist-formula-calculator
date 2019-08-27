<?php declare(strict_types=1); 

namespace DrdPlus\Codes\Skills;

/**
 * @method static CombinedSkillCode getIt($codeValue)
 * @method static CombinedSkillCode findIt($codeValue)
 */
class CombinedSkillCode extends SkillCode
{
    // COMBINED
    public const BIG_HANDWORK = 'big_handwork';
    public const COOKING = 'cooking';
    public const DANCING = 'dancing';
    public const DUSK_SIGHT = 'dusk_sight';
    public const FIGHT_WITH_BOWS = 'fight_with_bows';
    public const FIGHT_WITH_CROSSBOWS = 'fight_with_crossbows';
    public const FIRST_AID = 'first_aid';
    public const GAMBLING = 'gambling';
    public const HANDLING_WITH_ANIMALS = 'handling_with_animals';
    public const HANDWORK = 'handwork';
    public const HERBALISM = 'herbalism';
    public const HUNTING_AND_FISHING = 'hunting_and_fishing';
    public const KNOTTING = 'knotting';
    public const PAINTING = 'painting';
    public const PEDAGOGY = 'pedagogy';
    public const PLAYING_ON_MUSIC_INSTRUMENT = 'playing_on_music_instrument';
    public const SEDUCTION = 'seduction';
    public const SHOWMANSHIP = 'showmanship';
    public const SINGING = 'singing';
    public const STATUARY = 'statuary';
    public const TEACHING = 'teaching';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::BIG_HANDWORK,
            self::COOKING,
            self::DANCING,
            self::DUSK_SIGHT,
            self::FIGHT_WITH_BOWS,
            self::FIGHT_WITH_CROSSBOWS,
            self::FIRST_AID,
            self::GAMBLING,
            self::HANDLING_WITH_ANIMALS,
            self::HANDWORK,
            self::HERBALISM,
            self::HUNTING_AND_FISHING,
            self::KNOTTING,
            self::PAINTING,
            self::PEDAGOGY,
            self::PLAYING_ON_MUSIC_INSTRUMENT,
            self::SEDUCTION,
            self::SHOWMANSHIP,
            self::SINGING,
            self::STATUARY,
            self::TEACHING,
        ];
    }

    protected function fetchTranslations(): array
    {
        return [
            'en' => [
                self::BIG_HANDWORK => [self::$ONE => 'big handwork'],
                self::COOKING => [self::$ONE => 'cooking'],
                self::DANCING => [self::$ONE => 'dancing'],
                self::DUSK_SIGHT => [self::$ONE => 'dusk sight'],
                self::FIGHT_WITH_BOWS => [self::$ONE => 'fight with bows'],
                self::FIGHT_WITH_CROSSBOWS => [self::$ONE => 'fight with crossbows'],
                self::FIRST_AID => [self::$ONE => 'first aid'],
                self::GAMBLING => [self::$ONE => 'gambling'],
                self::HANDLING_WITH_ANIMALS => [self::$ONE => 'handling with animals'],
                self::HANDWORK => [self::$ONE => 'handwork'],
                self::HERBALISM => [self::$ONE => 'herbalism'],
                self::HUNTING_AND_FISHING => [self::$ONE => 'hunting and fishing'],
                self::KNOTTING => [self::$ONE => 'knotting'],
                self::PAINTING => [self::$ONE => 'painting'],
                self::PEDAGOGY => [self::$ONE => 'pedagogy'],
                self::PLAYING_ON_MUSIC_INSTRUMENT => [self::$ONE => 'playing on music instrument'],
                self::SEDUCTION => [self::$ONE => 'seduction'],
                self::SHOWMANSHIP => [self::$ONE => 'showmanship'],
                self::SINGING => [self::$ONE => 'singing'],
                self::STATUARY => [self::$ONE => 'statuary'],
                self::TEACHING => [self::$ONE => 'teaching'],
            ],
            'cs' => [
                self::BIG_HANDWORK => [self::$ONE => 'velké ruční práce'],
                self::COOKING => [self::$ONE => 'vaření'],
                self::DANCING => [self::$ONE => 'tanec'],
                self::DUSK_SIGHT => [self::$ONE => 'šerozrakost'],
                self::FIGHT_WITH_BOWS => [self::$ONE => 'boj s luky'],
                self::FIGHT_WITH_CROSSBOWS => [self::$ONE => 'boj s kušemi'],
                self::FIRST_AID => [self::$ONE => 'první pomoc'],
                self::GAMBLING => [self::$ONE => 'hazardní hry'],
                self::HANDLING_WITH_ANIMALS => [self::$ONE => 'zacházení se zvířaty'],
                self::HANDWORK => [self::$ONE => 'ruční práce'],
                self::HERBALISM => [self::$ONE => 'bylinkářství'],
                self::HUNTING_AND_FISHING => [self::$ONE => 'lov a rybolov'],
                self::KNOTTING => [self::$ONE => 'uzlování'],
                self::PAINTING => [self::$ONE => 'malování'],
                self::PEDAGOGY => [self::$ONE => 'vychovatelství'],
                self::PLAYING_ON_MUSIC_INSTRUMENT => [self::$ONE => 'hra na hudební nástroj'],
                self::SEDUCTION => [self::$ONE => 'svádění'],
                self::SHOWMANSHIP => [self::$ONE => 'herectví'],
                self::SINGING => [self::$ONE => 'zpěv'],
                self::STATUARY => [self::$ONE => 'sochařství'],
                self::TEACHING => [self::$ONE => 'vyučování'],
            ],
        ];
    }

}