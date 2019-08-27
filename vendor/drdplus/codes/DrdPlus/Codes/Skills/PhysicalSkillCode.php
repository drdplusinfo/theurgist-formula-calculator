<?php declare(strict_types=1);

namespace DrdPlus\Codes\Skills;

/**
 * @method static PhysicalSkillCode getIt($codeValue)
 * @method static PhysicalSkillCode findIt($codeValue)
 */
class PhysicalSkillCode extends SkillCode
{
    // PHYSICAL
    public const ARMOR_WEARING = 'armor_wearing';
    public const ATHLETICS = 'athletics';
    public const BLACKSMITHING = 'blacksmithing';
    public const BOAT_DRIVING = 'boat_driving';
    public const CART_DRIVING = 'cart_driving';
    public const CITY_MOVING = 'city_moving';
    public const CLIMBING_AND_HILLWALKING = 'climbing_and_hillwalking';
    public const FAST_MARSH = 'fast_marsh';
    public const FIGHT_UNARMED = 'fight_unarmed';
    public const FIGHT_WITH_AXES = 'fight_with_axes';
    public const FIGHT_WITH_KNIVES_AND_DAGGERS = 'fight_with_knives_and_daggers';
    public const FIGHT_WITH_MACES_AND_CLUBS = 'fight_with_maces_and_clubs';
    public const FIGHT_WITH_MORNINGSTARS_AND_MORGENSTERNS = 'fight_with_morningstars_and_morgensterns';
    public const FIGHT_WITH_SABERS_AND_BOWIE_KNIVES = 'fight_with_sabers_and_bowie_knives';
    public const FIGHT_WITH_SHIELDS = 'fight_with_shields'; // do not search this in rules, it is additional
    public const FIGHT_WITH_STAFFS_AND_SPEARS = 'fight_with_staffs_and_spears';
    public const FIGHT_WITH_SWORDS = 'fight_with_swords';
    public const FIGHT_WITH_THROWING_WEAPONS = 'fight_with_throwing_weapons';
    public const FIGHT_WITH_TWO_WEAPONS = 'fight_with_two_weapons';
    public const FIGHT_WITH_VOULGES_AND_TRIDENTS = 'fight_with_voulges_and_tridents';
    public const FLYING = 'flying';
    public const FOREST_MOVING = 'forest_moving';
    public const MOVING_IN_MOUNTAINS = 'moving_in_mountains';
    public const RIDING = 'riding';
    public const SAILING = 'sailing';
    public const SHIELD_USAGE = 'shield_usage';
    public const SWIMMING = 'swimming';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::ARMOR_WEARING,
            self::ATHLETICS,
            self::BLACKSMITHING,
            self::BOAT_DRIVING,
            self::CART_DRIVING,
            self::CITY_MOVING,
            self::CLIMBING_AND_HILLWALKING,
            self::FAST_MARSH,
            self::FIGHT_UNARMED,
            self::FIGHT_WITH_AXES,
            self::FIGHT_WITH_KNIVES_AND_DAGGERS,
            self::FIGHT_WITH_MACES_AND_CLUBS,
            self::FIGHT_WITH_MORNINGSTARS_AND_MORGENSTERNS,
            self::FIGHT_WITH_SABERS_AND_BOWIE_KNIVES,
            self::FIGHT_WITH_SHIELDS,
            self::FIGHT_WITH_STAFFS_AND_SPEARS,
            self::FIGHT_WITH_SWORDS,
            self::FIGHT_WITH_THROWING_WEAPONS,
            self::FIGHT_WITH_TWO_WEAPONS,
            self::FIGHT_WITH_VOULGES_AND_TRIDENTS,
            self::FLYING,
            self::FOREST_MOVING,
            self::MOVING_IN_MOUNTAINS,
            self::RIDING,
            self::SAILING,
            self::SHIELD_USAGE,
            self::SWIMMING,
        ];
    }

    protected function fetchTranslations(): array
    {
        return [
            'en' => [
                self::ARMOR_WEARING => [self::$ONE => 'armor wearing'],
                self::ATHLETICS => [self::$ONE => 'athletics'],
                self::BLACKSMITHING => [self::$ONE => 'blacksmithing'],
                self::BOAT_DRIVING => [self::$ONE => 'boat driving'],
                self::CART_DRIVING => [self::$ONE => 'cart driving'],
                self::CITY_MOVING => [self::$ONE => 'city moving'],
                self::CLIMBING_AND_HILLWALKING => [self::$ONE => 'climbing and hillwalking'],
                self::FAST_MARSH => [self::$ONE => 'fast marsh'],
                self::FIGHT_UNARMED => [self::$ONE => 'fight unarmed'],
                self::FIGHT_WITH_AXES => [self::$ONE => 'fight with axes'],
                self::FIGHT_WITH_KNIVES_AND_DAGGERS => [self::$ONE => 'fight with knives and daggers'],
                self::FIGHT_WITH_MACES_AND_CLUBS => [self::$ONE => 'fight with maces and clubs'],
                self::FIGHT_WITH_MORNINGSTARS_AND_MORGENSTERNS => [self::$ONE => 'fight with morningstars and morgensterns'],
                self::FIGHT_WITH_SABERS_AND_BOWIE_KNIVES => [self::$ONE => 'fight with sabers and bowie knives'],
                self::FIGHT_WITH_SHIELDS => [self::$ONE => 'fight with shields'],
                self::FIGHT_WITH_STAFFS_AND_SPEARS => [self::$ONE => 'fight with staffs and spears'],
                self::FIGHT_WITH_SWORDS => [self::$ONE => 'fight with swords'],
                self::FIGHT_WITH_THROWING_WEAPONS => [self::$ONE => 'fight with throwing weapons'],
                self::FIGHT_WITH_TWO_WEAPONS => [self::$ONE => 'fight with two weapons'],
                self::FIGHT_WITH_VOULGES_AND_TRIDENTS => [self::$ONE => 'fight with voulges and tridents'],
                self::FLYING => [self::$ONE => 'flying'],
                self::FOREST_MOVING => [self::$ONE => 'forest moving'],
                self::MOVING_IN_MOUNTAINS => [self::$ONE => 'moving in mountains'],
                self::RIDING => [self::$ONE => 'riding'],
                self::SAILING => [self::$ONE => 'sailing'],
                self::SHIELD_USAGE => [self::$ONE => 'shield usage'],
                self::SWIMMING => [self::$ONE => 'swimming'],
            ],
            'cs' => [
                self::ARMOR_WEARING => [self::$ONE => 'nošení zbroje'],
                self::ATHLETICS => [self::$ONE => 'atletika'],
                self::BLACKSMITHING => [self::$ONE => 'kovářství'],
                self::BOAT_DRIVING => [self::$ONE => 'ovládání loďky'],
                self::CART_DRIVING => [self::$ONE => 'řízení vozu'],
                self::CITY_MOVING => [self::$ONE => 'pohyb ve městě'],
                self::CLIMBING_AND_HILLWALKING => [self::$ONE => 'šplh a lezení'],
                self::FAST_MARSH => [self::$ONE => 'rychlý pochod'],
                self::FIGHT_UNARMED => [self::$ONE => 'boj beze zbraně'],
                self::FIGHT_WITH_AXES => [self::$ONE => 'boj se sekerami'],
                self::FIGHT_WITH_KNIVES_AND_DAGGERS => [self::$ONE => 'boj s noži a dýkami'],
                self::FIGHT_WITH_MACES_AND_CLUBS => [self::$ONE => 'boj s palicemi a kyji'],
                self::FIGHT_WITH_MORNINGSTARS_AND_MORGENSTERNS => [self::$ONE => 'boj se řemdihy a bijáky'],
                self::FIGHT_WITH_SABERS_AND_BOWIE_KNIVES => [self::$ONE => 'boj se šavlemi a tesáky'],
                self::FIGHT_WITH_SHIELDS => [self::$ONE => 'boj se štítem'],
                self::FIGHT_WITH_STAFFS_AND_SPEARS => [self::$ONE => 'boj s holemi a kopími'],
                self::FIGHT_WITH_SWORDS => [self::$ONE => 'boj s meči'],
                self::FIGHT_WITH_THROWING_WEAPONS => [self::$ONE => 'boj s vrhacími zbraněmi'],
                self::FIGHT_WITH_TWO_WEAPONS => [self::$ONE => 'boj se dvěma zbraněmi'],
                self::FIGHT_WITH_VOULGES_AND_TRIDENTS => [self::$ONE => 'boj se sudlicemi a trojzubci'],
                self::FLYING => [self::$ONE => 'letectví'],
                self::FOREST_MOVING => [self::$ONE => 'pohyb v lese'],
                self::MOVING_IN_MOUNTAINS => [self::$ONE => 'pohyb v horách'],
                self::RIDING => [self::$ONE => 'jezdectví'],
                self::SAILING => [self::$ONE => 'námořnictví'],
                self::SHIELD_USAGE => [self::$ONE => 'používání štítu'],
                self::SWIMMING => [self::$ONE => 'plavání'],
            ],
        ];
    }

}