<?php
namespace DrdPlus\Codes\Wizard;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static SpellCode getIt($codeValue)
 * @method static SpellCode findIt($codeValue)
 */
class SpellCode extends TranslatableCode
{
    // TIME-SPACE
    public const MAGIC_ARMOR = 'magic_armor';
    public const PROJECTILE = 'projectile';
    public const MIST = 'mist';
    public const TWINKLING = 'twinkling';
    public const SILENT_STEP = 'silent_step';
    public const DARK = 'dark';
    public const BARRIER = 'barrier';
    public const BOOMERANG = 'boomerang';
    public const CARPET = 'carpet';
    public const LOOK = 'look';
    public const SHRINK = 'shrink';
    public const CLOAK_OF_DARKNESS = 'cloak_of_darkness';
    public const RUN_SILENTLY = 'run_silently';
    public const TRACED_MISSILE = 'traced_missile';
    public const PROTECT = 'protect';
    public const INVISIBILITY = 'invisibility';
    public const QUICK_LEGS = 'quick_legs';
    public const SLOWDOWN = 'slowdown';
    public const TO_HOME = 'to_home';
    public const TOT = 'tot';
    public const CARRISHIELD = 'carrishield';
    public const TELEKINESIS = 'telekinesis';
    public const TRANSPOSITION = 'transposition';
    public const SEVEN_MILES_STEP = 'seven_miles_step';
    public const SYRUP = 'syrup';
    public const RUSH = 'rush';
    public const VIEW = 'view';
    public const ROLLER = 'roller';
    public const DOUBLED_MISSILE = 'doubled_missile';
    public const ASTRAL_POWER = 'astral_power';
    public const TRANSFER = 'transfer';
    public const SILENT_DOMAIN = 'silent_domain';
    public const TRACED_TELEPORTATION = 'traced_teleportation';
    public const DRAGON_SHIELD = 'dragon_shield';
    public const PLACE = 'place';
    public const BEANPOLE = 'beanpole';
    public const CRYSTAL = 'crystal';
    public const DIVERSION = 'diversion';
    public const CIRCLE_OF_SAFETY = 'circle_of_safety';
    public const POWER_OVER_SPACE = 'power_over_space';

    /**
     * @return array|string[]
     */
    public static function getTimeSpaceSpellCodes(): array
    {
        return [
            self::MAGIC_ARMOR,
            self::PROJECTILE,
            self::MIST,
            self::TWINKLING,
            self::SILENT_STEP,
            self::DARK,
            self::BARRIER,
            self::BOOMERANG,
            self::CARPET,
            self::LOOK,
            self::SHRINK,
            self::CLOAK_OF_DARKNESS,
            self::RUN_SILENTLY,
            self::TRACED_MISSILE,
            self::PROTECT,
            self::INVISIBILITY,
            self::QUICK_LEGS,
            self::SLOWDOWN,
            self::TO_HOME,
            self::TOT,
            self::CARRISHIELD,
            self::TELEKINESIS,
            self::TRANSPOSITION,
            self::SEVEN_MILES_STEP,
            self::SYRUP,
            self::RUSH,
            self::VIEW,
            self::ROLLER,
            self::DOUBLED_MISSILE,
            self::ASTRAL_POWER,
            self::TRANSFER,
            self::SILENT_DOMAIN,
            self::TRACED_TELEPORTATION,
            self::DRAGON_SHIELD,
            self::PLACE,
            self::BEANPOLE,
            self::CRYSTAL,
            self::DIVERSION,
            self::CIRCLE_OF_SAFETY,
            self::POWER_OVER_SPACE,
        ];
    }

    // ENERGETIC MAGIC
    public const MAROON = 'maroon';
    public const BURN = 'burn';
    public const WARM_UP = 'warm_up';
    public const LIGHT = 'light';
    public const HEFT = 'heft';
    public const CALM_DOWN = 'calm_down';
    public const WEIGHTLESS = 'weightless';
    public const BOXER_SHORTS = 'boxer_shorts';
    public const LOOK_BACK = 'look_back';
    public const DISCHARGE = 'discharge';
    public const FIREBALL = 'fireball';
    public const FIRE_HORNETS = 'fire_hornets';
    public const EVAPORATE = 'evaporate';
    public const FREEZE = 'freeze';
    public const HAMMER = 'hammer';
    public const BALL_LIGHTNING = 'ball_lightning';
    public const MELT_METAL = 'melt_metal';
    public const QUENCH = 'quench';
    public const NEKRAKOSA = 'nekrakosa';
    public const FIRE_ARROWS = 'fire_arrows';
    public const FLAMING_SWORD = 'flaming_sword';
    public const PERISH_IN_SPASMS = 'perish_in_spasms';
    public const BRIDGE = 'bridge';
    public const FLAME_TONGUE = 'flame_tongue';
    public const DEATH_IN_FLAMES = 'death_in_flames';
    public const TRACED_DISCHARGE = 'traced_discharge';
    public const SPARKING_DISCHARGE = 'sparking_discharge';
    public const LIGHTING = 'lighting';
    public const SUN_RAY = 'sun_ray';
    public const JAIL_FROM_FLAMES = 'jail_from_flames';
    public const LIGHTNING = 'lightning';
    public const BLACK_FROST = 'black_frost';
    public const CLEANSING_BY_FIRE = 'cleansing_by_fire';
    public const FLAME_WHIP = 'flame_whip';
    public const DECAPITATION = 'decapitation';
    public const SPARKLING_SIGHT = 'sparkling_sight';
    public const TRANSPOSED_DISCHARGE = 'transposed_discharge';
    public const WRATH_OF_GOD = 'wrath_of_god';
    public const FIREWALL = 'firewall';
    public const WATTER_WALK = 'watter_walk';

    /**
     * @return array|string[]
     */
    public static function getEnergeticSpellCodes(): array
    {
        return [
            self::MAROON,
            self::BURN,
            self::WARM_UP,
            self::LIGHT,
            self::HEFT,
            self::CALM_DOWN,
            self::WEIGHTLESS,
            self::BOXER_SHORTS,
            self::LOOK_BACK,
            self::DISCHARGE,
            self::FIREBALL,
            self::FIRE_HORNETS,
            self::EVAPORATE,
            self::FREEZE,
            self::HAMMER,
            self::BALL_LIGHTNING,
            self::MELT_METAL,
            self::QUENCH,
            self::NEKRAKOSA,
            self::FIRE_ARROWS,
            self::FLAMING_SWORD,
            self::PERISH_IN_SPASMS,
            self::BRIDGE,
            self::FLAME_TONGUE,
            self::DEATH_IN_FLAMES,
            self::TRACED_DISCHARGE,
            self::SPARKING_DISCHARGE,
            self::LIGHTING,
            self::SUN_RAY,
            self::JAIL_FROM_FLAMES,
            self::LIGHTNING,
            self::BLACK_FROST,
            self::CLEANSING_BY_FIRE,
            self::FLAME_WHIP,
            self::DECAPITATION,
            self::SPARKLING_SIGHT,
            self::TRANSPOSED_DISCHARGE,
            self::WRATH_OF_GOD,
            self::FIREWALL,
            self::WATTER_WALK,
        ];
    }

    // MATERIAL MAGIC
    public const NOT_EVEN_HIT = 'not_even_hit';
    public const TODAY_YOU_WILL_TRY_IT_WITHOUT_WEAPON = 'today_you_will_try_it_without_weapon';
    public const I_WILL_RESCUE_YOU = 'i_will_rescue_you';
    public const STONE_KISS = 'stone_kiss';
    public const SHOCKWAVE = 'shockwave';
    public const HIT = 'hit';
    public const CLOSE_THE_BAR = 'close_the_bar';
    public const ONE_SWORD_PLEASE = 'one_sword_please';
    public const HAIRDRESSER = 'hairdresser';
    public const TAKE_OF_YOUR_ARMOR_BUDDY = 'take_of_your_armor_buddy';
    public const SHIELD = 'shield';
    public const GELATIN_LOCK = 'gelatin_lock';
    public const STAFF = 'staff';
    public const AIR_CAPSULE = 'air_capsule';
    public const FIRE_BREATH = 'fire_breath';
    public const UP_THERE = 'up_there';
    public const I_KNOW_SHORTCUT = 'i_know_shortcut';
    public const HOW_MUCH_YOU_CAN_CARRY = 'how_much_you_can_carry';
    public const CRUSH = 'crush';
    public const MAKE_A_PLACE_MOB = 'make_a_place_mob';
    public const AIR_SHIELD = 'air_shield';
    public const ICE_SWORD = 'ice_sword';
    public const MISTY_CLOUD = 'misty_cloud';
    public const SLIPPERY_ICE = 'slippery_ice';
    public const STEEL_TRAP = 'steel_trap';
    public const CHIPPINGS = 'chippings';
    public const COME_TO_SAFEETY = 'come_to_safeety';
    public const WIND = 'wind';
    public const BECOME_ROOTED = 'become_rooted';
    public const MUD_BATH = 'mud_bath';
    public const WEB = 'web';
    public const SHAPE_CHANGE = 'shape_change';
    public const PARALYSE = 'paralyse';
    public const ROLLHAM = 'rollham';
    public const NOBODY_IS_GOING_THIS_WAY = 'nobody_is_going_this_way';
    public const WATER_BREATH = 'water_breath';
    public const INVISIBLE_SWORD = 'invisible_sword';
    public const BURIED_ALIVE = 'buried_alive';
    public const GO_WITHOUT_FEAR_YOU_WILL_NOT_FALL = 'go_without_fear_you_will_not_fall';
    public const JOY_TO_THE_BONE = 'joy_to_the_bone';

    /**
     * @return array
     */
    public static function getMaterialSpellCodes(): array
    {
        return [
            self::NOT_EVEN_HIT,
            self::TODAY_YOU_WILL_TRY_IT_WITHOUT_WEAPON,
            self::I_WILL_RESCUE_YOU,
            self::STONE_KISS,
            self::SHOCKWAVE,
            self::HIT,
            self::CLOSE_THE_BAR,
            self::ONE_SWORD_PLEASE,
            self::HAIRDRESSER,
            self::TAKE_OF_YOUR_ARMOR_BUDDY,
            self::SHIELD,
            self::GELATIN_LOCK,
            self::STAFF,
            self::AIR_CAPSULE,
            self::FIRE_BREATH,
            self::UP_THERE,
            self::I_KNOW_SHORTCUT,
            self::HOW_MUCH_YOU_CAN_CARRY,
            self::CRUSH,
            self::MAKE_A_PLACE_MOB,
            self::AIR_SHIELD,
            self::ICE_SWORD,
            self::MISTY_CLOUD,
            self::SLIPPERY_ICE,
            self::STEEL_TRAP,
            self::CHIPPINGS,
            self::COME_TO_SAFEETY,
            self::WIND,
            self::BECOME_ROOTED,
            self::MUD_BATH,
            self::WEB,
            self::SHAPE_CHANGE,
            self::PARALYSE,
            self::ROLLHAM,
            self::NOBODY_IS_GOING_THIS_WAY,
            self::WATER_BREATH,
            self::INVISIBLE_SWORD,
            self::BURIED_ALIVE,
            self::GO_WITHOUT_FEAR_YOU_WILL_NOT_FALL,
            self::JOY_TO_THE_BONE,
        ];
    }

    // INVESTIGATIVE SPELLS
    public const GOOD_TABLE = 'good_table';
    public const WHERE_IS_MY_PIPE = 'where_is_my_pipe';
    public const WHAT_STREW_IS_IT = 'what_strew_is_it';
    public const KICK_OVER_HERE = 'kick_over_here';
    public const REVEAL_MAGIC = 'reveal_magic';
    public const BACK = 'back';
    public const SAFETY_FIRST = 'safety_first';
    public const SEARCH = 'search';
    public const EAVESDROPPING = 'eavesdropping';
    public const EXPLORATION_EYE = 'exploration_eye';
    public const FISH_EYE = 'fish_eye';
    public const WHAT_IS_IT = 'what_is_it';
    public const I_WONDER_WHERE = 'i_wonder_where';
    public const WHERE_I_AM = 'where_i_am';
    public const VIEW_FROM_ABOVE = 'view_from_above';
    public const WEAKNESS = 'weakness';
    public const WHOSE_IS_THIS = 'whose_is_this';
    public const SIGHTS = 'sights';
    public const TRANSLATE = 'translate';
    public const CYPHER = 'cypher';
    public const SEARCH_VOLUME = 'search_volume';
    public const QUICK_READING = 'quick_reading';
    public const BEDBUG = 'bedbug';
    public const NIGHT_VISION = 'night_vision';
    public const FIND_INVISIBILITY = 'find_invisibility';
    public const NOTHING_WILL_HAPPEN_TO_YOU = 'nothing_will_happen_to_you';
    public const REVEAL_POISON = 'reveal_poison';
    public const HEAT_VISION = 'heat_vision';
    public const GENIUS_LOCI = 'genius_loci';
    public const WHERE_WAS_THAT = 'where_was_that';
    public const WHEN_DID_IT_HAPPEN = 'when_did_it_happen';
    public const MAGIC_VISION = 'magic_vision';
    public const LIAR = 'liar';
    public const FIND = 'find';
    public const HIDE_SLANDER = 'hide_slander';
    public const CREATE_SLANDER = 'create_slander';
    public const DECRYPT = 'decrypt';
    public const COMPROMISE_CREATURE = 'compromise_creature';
    public const SORE = 'sore';
    public const CHECK_INFORMATION = 'check_information';

    /**
     * @return array|string[]
     */
    public static function getInvestigativeSpellCodes(): array
    {
        return [
            self::GOOD_TABLE,
            self::WHERE_IS_MY_PIPE,
            self::WHAT_STREW_IS_IT,
            self::KICK_OVER_HERE,
            self::REVEAL_MAGIC,
            self::BACK,
            self::SAFETY_FIRST,
            self::SEARCH,
            self::EAVESDROPPING,
            self::EXPLORATION_EYE,
            self::FISH_EYE,
            self::WHAT_IS_IT,
            self::I_WONDER_WHERE,
            self::WHERE_I_AM,
            self::VIEW_FROM_ABOVE,
            self::WEAKNESS,
            self::WHOSE_IS_THIS,
            self::SIGHTS,
            self::TRANSLATE,
            self::CYPHER,
            self::SEARCH_VOLUME,
            self::QUICK_READING,
            self::BEDBUG,
            self::NIGHT_VISION,
            self::FIND_INVISIBILITY,
            self::NOTHING_WILL_HAPPEN_TO_YOU,
            self::REVEAL_POISON,
            self::HEAT_VISION,
            self::GENIUS_LOCI,
            self::WHERE_WAS_THAT,
            self::WHEN_DID_IT_HAPPEN,
            self::MAGIC_VISION,
            self::LIAR,
            self::FIND,
            self::HIDE_SLANDER,
            self::CREATE_SLANDER,
            self::DECRYPT,
            self::COMPROMISE_CREATURE,
            self::SORE,
            self::CHECK_INFORMATION,
        ];
    }

    public const COLORS_AND_SHAPES = 'colors_and_shapes';
    public const ENJOY_YOURSELF = 'enjoy_yourself';
    public const LOOK_AT_ME = 'look_at_me';
    public const NOTICE = 'notice';
    public const PATCH = 'patch';
    public const NO_FEARS = 'no_fears';
    public const CAGE = 'cage';
    public const FOCUS = 'focus';
    public const COME_HERE = 'come_here';
    public const SLEEP_WELL = 'sleep_well';
    public const BASTION = 'bastion';
    public const CZARDAS = 'czardas';
    public const IGNORE_ME = 'ignore_me';
    public const STAND_OUT = 'stand_out';
    public const BRACE_YOURSELF = 'brace_yourself';
    public const PAIN = 'pain';
    public const WHAT_ARE_YOU_THINKING_ABOUT = 'what_are_you_thinking_about';
    public const BLINDNESS = 'blindness';
    public const FEAR = 'fear';
    public const BERSERK = 'berserk';
    public const I_AM_NOT_HERE_AT_ALL = 'i_am_not_here_at_all';
    public const DREAM = 'dream';
    public const DARKNESS = 'darkness';
    public const IDEA = 'idea';
    public const CONCENTRATION = 'concentration';
    public const PARTY = 'party';
    public const WALL_UP = 'wall_up';
    public const TRUST = 'trust';
    public const LUST = 'lust';
    public const YOU_WILL_NOT_SEE_MEE = 'you_will_not_see_mee';
    public const RESPECT = 'respect';
    public const DAM = 'dam';
    public const PAST = 'past';
    public const NO_ONE_ANYWHERE = 'no_one_anywhere';
    public const FRIEND = 'friend';
    public const DELIRIUM = 'delirium';
    public const PILLAR_OF_SALT = 'pillar_of_salt';
    public const IRE = 'ire';
    public const GO_AND_DO_AS_I_ASK = 'go_and_do_as_i_ask';
    public const PHANTASM = 'phantasm';

    /**
     * @return array|string[]
     */
    public static function getMentalSpellCodes(): array
    {
        return [
            self::COLORS_AND_SHAPES,
            self::ENJOY_YOURSELF,
            self::LOOK_AT_ME,
            self::NOTICE,
            self::PATCH,
            self::NO_FEARS,
            self::CAGE,
            self::FOCUS,
            self::COME_HERE,
            self::SLEEP_WELL,
            self::BASTION,
            self::CZARDAS,
            self::IGNORE_ME,
            self::STAND_OUT,
            self::BRACE_YOURSELF,
            self::PAIN,
            self::WHAT_ARE_YOU_THINKING_ABOUT,
            self::BLINDNESS,
            self::FEAR,
            self::BERSERK,
            self::I_AM_NOT_HERE_AT_ALL,
            self::DREAM,
            self::DARKNESS,
            self::IDEA,
            self::CONCENTRATION,
            self::PARTY,
            self::WALL_UP,
            self::TRUST,
            self::LUST,
            self::YOU_WILL_NOT_SEE_MEE,
            self::RESPECT,
            self::DAM,
            self::PAST,
            self::NO_ONE_ANYWHERE,
            self::FRIEND,
            self::DELIRIUM,
            self::PILLAR_OF_SALT,
            self::IRE,
            self::GO_AND_DO_AS_I_ASK,
            self::PHANTASM,
        ];
    }

    // VITAL MAGIC
    public const QUINT_OF_SENSES = 'quint_of_senses';
    public const TORTURE = 'torture';
    public const LUBBER = 'lubber';
    public const STUN = 'stun';
    public const HICCUP = 'hiccup';
    public const FIZZLE_OUT = 'fizzle_out';
    public const TRIP_UP = 'trip_up';
    public const CAROUSEL = 'carousel';
    public const CADAVERIC_SLEEP = 'cadaveric_sleep';
    public const DYE_SKIN = 'dye_skin';
    public const EXHAUST = 'exhaust';
    public const CHANGE_FACE = 'change_face';
    public const BARBER = 'barber';
    public const LET_WOUNDS_BLEED = 'let_wounds_bleed';
    public const SUMMON_UNDEADS = 'summon_undeads';
    public const STRAITJACKET = 'straitjacket';
    public const TEAR_OF_EARS = 'tear_of_ears';
    public const PIERCE_EYES = 'pierce_eyes';
    public const CUT_OUT_TONGUE = 'cut_out_tongue';
    public const CRIPPLE_HAND = 'cripple_hand';
    public const BLEED_FROM_EYES = 'bleed_from_eyes';
    public const METAMORPHOSIS_INTO = 'metamorphosis_into';
    public const VOODOO = 'voodoo';
    public const BECOME_TRANSLUCENT = 'become_translucent';
    public const TOUCH_OF_DEATH = 'touch_of_death';
    public const BONE_BREAKER = 'bone_breaker';
    public const NIMBLENESS = 'nimbleness';
    public const BRING_ON_ALL_ILLNESS = 'bring_on_all_illness';
    public const INSOMNIA = 'insomnia';
    public const CONTROL_ZOMBIE = 'control_zombie';
    public const LEAKAGE = 'leakage';
    public const SNAIL_SHELL = 'snail_shell';
    public const UNDEAD_SERVANT = 'undead_servant';
    public const TIT_FOR_TAT = 'tit_for_tat';
    public const DRAIN_VITALITY = 'drain_vitality';
    public const CONTROL_OVER_GHOSTS = 'control_over_ghosts';
    public const WITHER_AWAY = 'wither_away';
    public const BACK_TO_THE_GRAVE = 'back_to_the_grave';
    public const ANIMATE_UNDEAD = 'animate_undead';
    public const GLANCE_AT_WORLD_BY_EYES = 'glance_at_world_by_eyes';

    /**
     * @return array|string[]
     */
    public static function getVitalSpellCodes(): array
    {
        return [
            self::QUINT_OF_SENSES,
            self::TORTURE,
            self::LUBBER,
            self::STUN,
            self::HICCUP,
            self::FIZZLE_OUT,
            self::TRIP_UP,
            self::CAROUSEL,
            self::CADAVERIC_SLEEP,
            self::DYE_SKIN,
            self::EXHAUST,
            self::CHANGE_FACE,
            self::BARBER,
            self::LET_WOUNDS_BLEED,
            self::SUMMON_UNDEADS,
            self::STRAITJACKET,
            self::TEAR_OF_EARS,
            self::PIERCE_EYES,
            self::CUT_OUT_TONGUE,
            self::CRIPPLE_HAND,
            self::BLEED_FROM_EYES,
            self::METAMORPHOSIS_INTO,
            self::VOODOO,
            self::BECOME_TRANSLUCENT,
            self::TOUCH_OF_DEATH,
            self::BONE_BREAKER,
            self::NIMBLENESS,
            self::BRING_ON_ALL_ILLNESS,
            self::INSOMNIA,
            self::CONTROL_ZOMBIE,
            self::LEAKAGE,
            self::SNAIL_SHELL,
            self::UNDEAD_SERVANT,
            self::TIT_FOR_TAT,
            self::DRAIN_VITALITY,
            self::CONTROL_OVER_GHOSTS,
            self::WITHER_AWAY,
            self::BACK_TO_THE_GRAVE,
            self::ANIMATE_UNDEAD,
            self::GLANCE_AT_WORLD_BY_EYES,
        ];
    }

    public static function getPossibleValues(): array
    {
        return array_merge(
            self::getTimeSpaceSpellCodes(),
            self::getEnergeticSpellCodes(),
            self::getMaterialSpellCodes(),
            self::getInvestigativeSpellCodes(),
            self::getMentalSpellCodes(),
            self::getVitalSpellCodes()
        );
    }

    protected function fetchTranslations(): array
    {
        return [
            'en' => [
                // TIME-SPACE
                self::MAGIC_ARMOR => ['one' => 'magic armor'],
                self::PROJECTILE => ['one' => 'projectile'],
                self::MIST => ['one' => 'mist'],
                self::TWINKLING => ['one' => 'twinkling'],
                self::SILENT_STEP => ['one' => 'silent step'],
                self::DARK => ['one' => 'dark'],
                self::BARRIER => ['one' => 'barrier'],
                self::BOOMERANG => ['one' => 'boomerang'],
                self::CARPET => ['one' => 'carpet'],
                self::LOOK => ['one' => 'look'],
                self::SHRINK => ['one' => 'shrink'],
                self::CLOAK_OF_DARKNESS => ['one' => 'cloak of darkness'],
                self::RUN_SILENTLY => ['one' => 'run silently!'],
                self::TRACED_MISSILE => ['one' => 'traced missile'],
                self::PROTECT => ['one' => 'protect'],
                self::INVISIBILITY => ['one' => 'invisibility'],
                self::QUICK_LEGS => ['one' => 'quick legs'],
                self::SLOWDOWN => ['one' => 'slowdown'],
                self::TO_HOME => ['one' => 'to home'],
                self::TOT => ['one' => 'tot'],
                self::CARRISHIELD => ['one' => 'carrishield'],
                self::TELEKINESIS => ['one' => 'telekinesis'],
                self::TRANSPOSITION => ['one' => 'transposition'],
                self::SEVEN_MILES_STEP => ['one' => 'seven miles step'],
                self::SYRUP => ['one' => 'syrup'],
                self::RUSH => ['one' => 'rush'],
                self::VIEW => ['one' => 'view'],
                self::ROLLER => ['one' => 'roller'],
                self::DOUBLED_MISSILE => ['one' => 'doubled missile'],
                self::ASTRAL_POWER => ['one' => 'astral power'],
                self::TRANSFER => ['one' => 'transfer'],
                self::SILENT_DOMAIN => ['one' => 'silent domain'],
                self::TRACED_TELEPORTATION => ['one' => 'traced teleportation'],
                self::DRAGON_SHIELD => ['one' => 'dragon shield'],
                self::PLACE => ['one' => 'place'],
                self::BEANPOLE => ['one' => 'beanpole'],
                self::CRYSTAL => ['one' => 'crystal'],
                self::DIVERSION => ['one' => 'diversion'],
                self::CIRCLE_OF_SAFETY => ['one' => 'circle of safety'],
                self::POWER_OVER_SPACE => ['one' => 'power over space'],
                // ENERGETIC MAGIC
                self::MAROON => ['one' => 'maroon'],
                self::BURN => ['one' => 'burn'],
                self::WARM_UP => ['one' => 'warm up'],
                self::LIGHT => ['one' => 'light'],
                self::HEFT => ['one' => 'heft'],
                self::CALM_DOWN => ['one' => 'calm down'],
                self::WEIGHTLESS => ['one' => 'weightless'],
                self::BOXER_SHORTS => ['one' => 'boxer shorts'],
                self::LOOK_BACK => ['one' => 'look back'],
                self::DISCHARGE => ['one' => 'discharge'],
                self::FIREBALL => ['one' => 'fireball'],
                self::FIRE_HORNETS => ['one' => 'fire hornets'],
                self::EVAPORATE => ['one' => 'evaporate'],
                self::FREEZE => ['one' => 'freeze'],
                self::HAMMER => ['one' => 'hammer'],
                self::BALL_LIGHTNING => ['one' => 'ball lightning'],
                self::MELT_METAL => ['one' => 'melt metal'],
                self::QUENCH => ['one' => 'quench'],
                self::NEKRAKOSA => ['one' => 'nekrakosa'],
                self::FIRE_ARROWS => ['one' => 'fire arrows'],
                self::FLAMING_SWORD => ['one' => 'flaming sword'],
                self::PERISH_IN_SPASMS => ['one' => 'perish in spasms'],
                self::BRIDGE => ['one' => 'bridge'],
                self::FLAME_TONGUE => ['one' => 'flame tongue'],
                self::DEATH_IN_FLAMES => ['one' => 'death in flames'],
                self::TRACED_DISCHARGE => ['one' => 'traced discharge'],
                self::SPARKING_DISCHARGE => ['one' => 'sparking discharge'],
                self::LIGHTING => ['one' => 'lighting'],
                self::SUN_RAY => ['one' => 'sun ray'],
                self::JAIL_FROM_FLAMES => ['one' => 'jail from flames'],
                self::LIGHTNING => ['one' => 'lightning'],
                self::BLACK_FROST => ['one' => 'black frost'],
                self::CLEANSING_BY_FIRE => ['one' => 'cleansing by fire'],
                self::FLAME_WHIP => ['one' => 'flame whip'],
                self::DECAPITATION => ['one' => 'decapitation'],
                self::SPARKLING_SIGHT => ['one' => 'sparkling sight'],
                self::TRANSPOSED_DISCHARGE => ['one' => 'transposed discharge'],
                self::WRATH_OF_GOD => ['one' => 'wrath of god'],
                self::FIREWALL => ['one' => 'firewall'],
                self::WATTER_WALK => ['one' => 'watter walk'],
                // MATERIAL MAGIC
                self::NOT_EVEN_HIT => ['one' => 'not even hit'],
                self::TODAY_YOU_WILL_TRY_IT_WITHOUT_WEAPON => ['one' => 'today you will try it without weapon'],
                self::I_WILL_RESCUE_YOU => ['one' => 'i will rescue you'],
                self::STONE_KISS => ['one' => 'stone kiss'],
                self::SHOCKWAVE => ['one' => 'shockwave'],
                self::HIT => ['one' => 'hit'],
                self::CLOSE_THE_BAR => ['one' => 'close the bar'],
                self::ONE_SWORD_PLEASE => ['one' => 'one sword please'],
                self::HAIRDRESSER => ['one' => 'hairdresser'],
                self::TAKE_OF_YOUR_ARMOR_BUDDY => ['one' => 'take of your armor buddy'],
                self::SHIELD => ['one' => 'shield'],
                self::GELATIN_LOCK => ['one' => 'gelatin lock'],
                self::STAFF => ['one' => 'staff'],
                self::AIR_CAPSULE => ['one' => 'air capsule'],
                self::FIRE_BREATH => ['one' => 'fire breath'],
                self::UP_THERE => ['one' => 'up there'],
                self::I_KNOW_SHORTCUT => ['one' => 'i know shortcut'],
                self::HOW_MUCH_YOU_CAN_CARRY => ['one' => 'how much you can carry'],
                self::CRUSH => ['one' => 'crush'],
                self::MAKE_A_PLACE_MOB => ['one' => 'make a place mob'],
                self::AIR_SHIELD => ['one' => 'air shield'],
                self::ICE_SWORD => ['one' => 'ice sword'],
                self::MISTY_CLOUD => ['one' => 'misty cloud'],
                self::SLIPPERY_ICE => ['one' => 'slippery ice'],
                self::STEEL_TRAP => ['one' => 'steel trap'],
                self::CHIPPINGS => ['one' => 'chippings'],
                self::COME_TO_SAFEETY => ['one' => 'come to safeety'],
                self::WIND => ['one' => 'wind'],
                self::BECOME_ROOTED => ['one' => 'become rooted'],
                self::MUD_BATH => ['one' => 'mud bath'],
                self::WEB => ['one' => 'web'],
                self::SHAPE_CHANGE => ['one' => 'shape change'],
                self::PARALYSE => ['one' => 'paralyse'],
                self::ROLLHAM => ['one' => 'rollham'],
                self::NOBODY_IS_GOING_THIS_WAY => ['one' => 'nobody is going this way'],
                self::WATER_BREATH => ['one' => 'water breath'],
                self::INVISIBLE_SWORD => ['one' => 'invisible sword'],
                self::BURIED_ALIVE => ['one' => 'buried alive'],
                self::GO_WITHOUT_FEAR_YOU_WILL_NOT_FALL => ['one' => 'go without fear you will not fall'],
                self::JOY_TO_THE_BONE => ['one' => 'joy to the bone'],
                // INVESTIGATING MAGIC
                self::GOOD_TABLE => 'good table',
                self::WHERE_IS_MY_PIPE => 'where is my pipe',
                self::WHAT_STREW_IS_IT => 'what strew is it',
                self::KICK_OVER_HERE => 'kick over here',
                self::REVEAL_MAGIC => 'reveal magic',
                self::BACK => 'back',
                self::SAFETY_FIRST => 'safety first',
                self::SEARCH => 'search',
                self::EAVESDROPPING => 'eavesdropping',
                self::EXPLORATION_EYE => 'exploration eye',
                self::FISH_EYE => 'fish eye',
                self::WHAT_IS_IT => 'what is it',
                self::I_WONDER_WHERE => 'i wonder where',
                self::WHERE_I_AM => 'where i am',
                self::VIEW_FROM_ABOVE => 'view from above',
                self::WEAKNESS => 'weakness',
                self::WHOSE_IS_THIS => 'whose is this',
                self::SIGHTS => 'sights',
                self::TRANSLATE => 'translate',
                self::CYPHER => 'cypher',
                self::SEARCH_VOLUME => 'search volume',
                self::QUICK_READING => 'quick reading',
                self::BEDBUG => 'bedbug',
                self::NIGHT_VISION => 'night vision',
                self::FIND_INVISIBILITY => 'find invisibility',
                self::NOTHING_WILL_HAPPEN_TO_YOU => 'nothing will happen to you',
                self::REVEAL_POISON => 'reveal poison',
                self::HEAT_VISION => 'heat vision',
                self::GENIUS_LOCI => 'genius loci',
                self::WHERE_WAS_THAT => 'where was that',
                self::WHEN_DID_IT_HAPPEN => 'when did it happen',
                self::MAGIC_VISION => 'magic vision',
                self::LIAR => 'liar',
                self::FIND => 'find',
                self::HIDE_SLANDER => 'hide slander',
                self::CREATE_SLANDER => 'create slander',
                self::DECRYPT => 'decrypt',
                self::COMPROMISE_CREATURE => 'compromise creature',
                self::SORE => 'sore',
                self::CHECK_INFORMATION => 'check information',
                // MENTAL SPELLS
                self::COLORS_AND_SHAPES => ['one' => 'colors and shapes'],
                self::ENJOY_YOURSELF => ['one' => 'enjoy yourself'],
                self::LOOK_AT_ME => ['one' => 'look at me'],
                self::NOTICE => ['one' => 'notice'],
                self::PATCH => ['one' => 'patch'],
                self::NO_FEARS => ['one' => 'no fears'],
                self::CAGE => ['one' => 'cage'],
                self::FOCUS => ['one' => 'focus'],
                self::COME_HERE => ['one' => 'come here'],
                self::SLEEP_WELL => ['one' => 'sleep well'],
                self::BASTION => ['one' => 'bastion'],
                self::CZARDAS => ['one' => 'czardas'],
                self::IGNORE_ME => ['one' => 'ignore me'],
                self::STAND_OUT => ['one' => 'stand out'],
                self::BRACE_YOURSELF => ['one' => 'brace yourself'],
                self::PAIN => ['one' => 'pain'],
                self::WHAT_ARE_YOU_THINKING_ABOUT => ['one' => 'what are you thinking about'],
                self::BLINDNESS => ['one' => 'blindness'],
                self::FEAR => ['one' => 'fear'],
                self::BERSERK => ['one' => 'berserk'],
                self::I_AM_NOT_HERE_AT_ALL => ['one' => 'i am not here at all'],
                self::DREAM => ['one' => 'dream'],
                self::DARKNESS => ['one' => 'darkness'],
                self::IDEA => ['one' => 'idea'],
                self::CONCENTRATION => ['one' => 'concentration'],
                self::PARTY => ['one' => 'party'],
                self::WALL_UP => ['one' => 'wall up'],
                self::TRUST => ['one' => 'trust'],
                self::LUST => ['one' => 'lust'],
                self::YOU_WILL_NOT_SEE_MEE => ['one' => 'you will not see mee'],
                self::RESPECT => ['one' => 'respect'],
                self::DAM => ['one' => 'dam'],
                self::PAST => ['one' => 'past'],
                self::NO_ONE_ANYWHERE => ['one' => 'no one anywhere'],
                self::FRIEND => ['one' => 'friend'],
                self::DELIRIUM => ['one' => 'delirium'],
                self::PILLAR_OF_SALT => ['one' => 'pillar of salt'],
                self::IRE => ['one' => 'ire'],
                self::GO_AND_DO_AS_I_ASK => ['one' => 'go and do as i ask'],
                self::PHANTASM => ['one' => 'phantasm'],
                // VITAL
                self::QUINT_OF_SENSES => ['one' => 'quint of senses'],
                self::TORTURE => ['one' => 'torture'],
                self::LUBBER => ['one' => 'lubber'],
                self::STUN => ['one' => 'stun'],
                self::HICCUP => ['one' => 'hiccup'],
                self::FIZZLE_OUT => ['one' => 'fizzle out'],
                self::TRIP_UP => ['one' => 'trip up'],
                self::CAROUSEL => ['one' => 'carousel'],
                self::CADAVERIC_SLEEP => ['one' => 'cadaveric sleep'],
                self::DYE_SKIN => ['one' => 'dye skin'],
                self::EXHAUST => ['one' => 'exhaust'],
                self::CHANGE_FACE => ['one' => 'change face'],
                self::BARBER => ['one' => 'barber'],
                self::LET_WOUNDS_BLEED => ['one' => 'let wounds bleed'],
                self::SUMMON_UNDEADS => ['one' => 'summon undeads'],
                self::STRAITJACKET => ['one' => 'straitjacket'],
                self::TEAR_OF_EARS => ['one' => 'tear of ears'],
                self::PIERCE_EYES => ['one' => 'pierce eyes'],
                self::CUT_OUT_TONGUE => ['one' => 'cut out tongue'],
                self::CRIPPLE_HAND => ['one' => 'cripple hand'],
                self::BLEED_FROM_EYES => ['one' => 'bleed from eyes'],
                self::METAMORPHOSIS_INTO => ['one' => 'metamorphosis into'],
                self::VOODOO => ['one' => 'voodoo'],
                self::BECOME_TRANSLUCENT => ['one' => 'become translucent'],
                self::TOUCH_OF_DEATH => ['one' => 'touch of death'],
                self::BONE_BREAKER => ['one' => 'bone breaker'],
                self::NIMBLENESS => ['one' => 'nimbleness'],
                self::BRING_ON_ALL_ILLNESS => ['one' => 'bring on all illness'],
                self::INSOMNIA => ['one' => 'insomnia'],
                self::CONTROL_ZOMBIE => ['one' => 'control zombie'],
                self::LEAKAGE => ['one' => 'leakage'],
                self::SNAIL_SHELL => ['one' => 'snail shell'],
                self::UNDEAD_SERVANT => ['one' => 'undead servant'],
                self::TIT_FOR_TAT => ['one' => 'tit for tat'],
                self::DRAIN_VITALITY => ['one' => 'drain vitality'],
                self::CONTROL_OVER_GHOSTS => ['one' => 'control over ghosts'],
                self::WITHER_AWAY => ['one' => 'wither away'],
                self::BACK_TO_THE_GRAVE => ['one' => 'back to the grave'],
                self::ANIMATE_UNDEAD => ['one' => 'animate undead'],
                self::GLANCE_AT_WORLD_BY_EYES => ['one' => 'glance at world by eyes'],
            ],
            'cs' => [
                // TIME-SPACE
                self::MAGIC_ARMOR => ['one' => 'magická zbroj'],
                self::PROJECTILE => ['one' => 'projektil'],
                self::MIST => ['one' => 'mlha'],
                self::TWINKLING => ['one' => 'okamžik'],
                self::SILENT_STEP => ['one' => 'tichý krok'],
                self::DARK => ['one' => 'tma'],
                self::BARRIER => ['one' => 'bariéra'],
                self::BOOMERANG => ['one' => 'bumerang'],
                self::CARPET => ['one' => 'koberec'],
                self::LOOK => ['one' => 'pohlédni'],
                self::SHRINK => ['one' => 'zmenšení'],
                self::CLOAK_OF_DARKNESS => ['one' => 'plášť temnoty'],
                self::RUN_SILENTLY => ['one' => 'běž potichu!'],
                self::TRACED_MISSILE => ['one' => 'trasovaná střela'],
                self::PROTECT => ['one' => 'ochraň'],
                self::INVISIBILITY => ['one' => 'neviditelnost'],
                self::QUICK_LEGS => ['one' => 'rychlé nohy'],
                self::SLOWDOWN => ['one' => 'zpomalení'],
                self::TO_HOME => ['one' => 'domů'],
                self::TOT => ['one' => 'prťavec'],
                self::CARRISHIELD => ['one' => 'štítonoš'],
                self::TELEKINESIS => ['one' => 'telekineze'],
                self::TRANSPOSITION => ['one' => 'transpozice'],
                self::SEVEN_MILES_STEP => ['one' => 'sedmimílový krok'],
                self::SYRUP => ['one' => 'sirup'],
                self::RUSH => ['one' => 'spěch'],
                self::VIEW => ['one' => 'výhled'],
                self::ROLLER => ['one' => 'roleta'],
                self::DOUBLED_MISSILE => ['one' => 'zdvojená střela'],
                self::ASTRAL_POWER => ['one' => 'astrální síla'],
                self::TRANSFER => ['one' => 'přenos'],
                self::SILENT_DOMAIN => ['one' => 'tichá doména'],
                self::TRACED_TELEPORTATION => ['one' => 'trasovaná teleportace'],
                self::DRAGON_SHIELD => ['one' => 'dračí štít'],
                self::PLACE => ['one' => 'místo'],
                self::BEANPOLE => ['one' => 'dlouhán'],
                self::CRYSTAL => ['one' => 'křišťál'],
                self::DIVERSION => ['one' => 'odklonění'],
                self::CIRCLE_OF_SAFETY => ['one' => 'kruh bezpečí'],
                self::POWER_OVER_SPACE => ['one' => 'moc nad prostorem'],
                // ENERGETIC MAGIC
                self::MAROON => ['one' => 'dělbuch'],
                self::BURN => ['one' => 'hoř'],
                self::WARM_UP => ['one' => 'ohřej'],
                self::LIGHT => ['one' => 'světlo'],
                self::HEFT => ['one' => 'tíha*'],
                self::CALM_DOWN => ['one' => 'zchlaď'],
                self::WEIGHTLESS => ['one' => 'beztíže'],
                self::BOXER_SHORTS => ['one' => 'boxerky'],
                self::LOOK_BACK => ['one' => 'ohlédni se'],
                self::DISCHARGE => ['one' => 'výboj'],
                self::FIREBALL => ['one' => 'ohnivá koule'],
                self::FIRE_HORNETS => ['one' => 'ohniví sršni'],
                self::EVAPORATE => ['one' => 'vypař'],
                self::FREEZE => ['one' => 'zmraz'],
                self::HAMMER => ['one' => 'kladivo'],
                self::BALL_LIGHTNING => ['one' => 'kulový blesk'],
                self::MELT_METAL => ['one' => 'roztav kov'],
                self::QUENCH => ['one' => 'uhas'],
                self::NEKRAKOSA => ['one' => 'nekrakosa'],
                self::FIRE_ARROWS => ['one' => 'ohnivé šípy'],
                self::FLAMING_SWORD => ['one' => 'plamenný meč'],
                self::PERISH_IN_SPASMS => ['one' => 'zhyň v křečích!'],
                self::BRIDGE => ['one' => 'most'],
                self::FLAME_TONGUE => ['one' => 'ohnivý jazyk'],
                self::DEATH_IN_FLAMES => ['one' => 'smrt v plamenech'],
                self::TRACED_DISCHARGE => ['one' => 'trasovaný výboj'],
                self::SPARKING_DISCHARGE => ['one' => 'jiskřící zbroj'],
                self::LIGHTING => ['one' => 'osvětlení'],
                self::SUN_RAY => ['one' => 'sluneční paprsek'],
                self::JAIL_FROM_FLAMES => ['one' => 'žalář z plamenů'],
                self::LIGHTNING => ['one' => 'blesk'],
                self::BLACK_FROST => ['one' => 'holomráz'],
                self::CLEANSING_BY_FIRE => ['one' => 'očista ohněm'],
                self::FLAME_WHIP => ['one' => 'plamenný bič'],
                self::DECAPITATION => ['one' => 'dekapitace'],
                self::SPARKLING_SIGHT => ['one' => 'jiskrný pohled'],
                self::TRANSPOSED_DISCHARGE => ['one' => 'transponovaný výboj'],
                self::WRATH_OF_GOD => ['one' => 'hněv boží'],
                self::FIREWALL => ['one' => 'stěna ohně'],
                self::WATTER_WALK => ['one' => 'vodošlap'],
                // MATERIAL MAGIC
                self::NOT_EVEN_HIT => ['one' => 'ani ránu'],
                self::TODAY_YOU_WILL_TRY_IT_WITHOUT_WEAPON => ['one' => 'dneska to zkusíš beze zbraně'],
                self::I_WILL_RESCUE_YOU => ['one' => 'já vás vysvobodím'],
                self::STONE_KISS => ['one' => 'kameňák'],
                self::SHOCKWAVE => ['one' => 'tlaková vlna'],
                self::HIT => ['one' => 'úder'],
                self::CLOSE_THE_BAR => ['one' => 'zavři na závoru'],
                self::ONE_SWORD_PLEASE => ['one' => 'jeden meč, prosím'],
                self::HAIRDRESSER => ['one' => 'kadeřník'],
                self::TAKE_OF_YOUR_ARMOR_BUDDY => ['one' => 'sundej si zbroj, kamaráde'],
                self::SHIELD => ['one' => 'štít'],
                self::GELATIN_LOCK => ['one' => 'želatinový zámek'],
                self::STAFF => ['one' => 'hůl'],
                self::AIR_CAPSULE => ['one' => 'kapsle vzduchu'],
                self::FIRE_BREATH => ['one' => 'ohnivý dech'],
                self::UP_THERE => ['one' => 'támhle nahoru'],
                self::I_KNOW_SHORTCUT => ['one' => 'znám zkratku'],
                self::HOW_MUCH_YOU_CAN_CARRY => ['one' => 'kolik uneseš…'],
                self::CRUSH => ['one' => 'rozdrť'],
                self::MAKE_A_PLACE_MOB => ['one' => 'udělejte mi místo, lůzo!'],
                self::AIR_SHIELD => ['one' => 'vzdušný štít'],
                self::ICE_SWORD => ['one' => 'ledový meč'],
                self::MISTY_CLOUD => ['one' => 'mlžný oblak'],
                self::SLIPPERY_ICE => ['one' => 'náledí'],
                self::STEEL_TRAP => ['one' => 'ocelová past'],
                self::CHIPPINGS => ['one' => 'odštěpky'],
                self::COME_TO_SAFEETY => ['one' => 'pojďte do bezpečí'],
                self::WIND => ['one' => 'vítr'],
                self::BECOME_ROOTED => ['one' => 'zapustíme kořeny'],
                self::MUD_BATH => ['one' => 'bahenní lázeň'],
                self::WEB => ['one' => 'pavučina'],
                self::SHAPE_CHANGE => ['one' => 'přeměna'],
                self::PARALYSE => ['one' => 'ochromení*'],
                self::ROLLHAM => ['one' => 'rolšunka'],
                self::NOBODY_IS_GOING_THIS_WAY => ['one' => 'tudy už nikdo nepůjde'],
                self::WATER_BREATH => ['one' => 'vodní dech'],
                self::INVISIBLE_SWORD => ['one' => 'neviditelný meč'],
                self::BURIED_ALIVE => ['one' => 'pohřben zaživa'],
                self::GO_WITHOUT_FEAR_YOU_WILL_NOT_FALL => ['one' => 'jdi bez obav, nepropadneš'],
                self::JOY_TO_THE_BONE => ['one' => 'radost až na kost'],
                // INVESTIGATING MAGIC
                self::GOOD_TABLE => ['one' => 'dobrý stůl'],
                self::WHERE_IS_MY_PIPE => ['one' => 'kde mám dýmku?'],
                self::WHAT_STREW_IS_IT => ['one' => 'kolik sype?'],
                self::KICK_OVER_HERE => ['one' => 'kopni semhle'],
                self::REVEAL_MAGIC => ['one' => 'odhal magii'],
                self::BACK => ['one' => 'zpátky'],
                self::SAFETY_FIRST => ['one' => 'bezpečnost především'],
                self::SEARCH => ['one' => 'hledej'],
                self::EAVESDROPPING => ['one' => 'odposlech'],
                self::EXPLORATION_EYE => ['one' => 'průzkumné oko'],
                self::FISH_EYE => ['one' => 'rybí oko'],
                self::WHAT_IS_IT => ['one' => 'co je tohle?'],
                self::I_WONDER_WHERE => ['one' => 'kampak?'],
                self::WHERE_I_AM => ['one' => 'kde jsem?'],
                self::VIEW_FROM_ABOVE => ['one' => 'nadhled'],
                self::WEAKNESS => ['one' => 'slabina'],
                self::WHOSE_IS_THIS => ['one' => 'čí je tohle?'],
                self::SIGHTS => ['one' => 'pamětihodnosti'],
                self::TRANSLATE => ['one' => 'přelož'],
                self::CYPHER => ['one' => 'šifra'],
                self::SEARCH_VOLUME => ['one' => 'hledej svazek'],
                self::QUICK_READING => ['one' => 'rychločtení'],
                self::BEDBUG => ['one' => 'štěnice'],
                self::NIGHT_VISION => ['one' => 'vidění za tmy'],
                self::FIND_INVISIBILITY => ['one' => 'najdi neviditelnost'],
                self::NOTHING_WILL_HAPPEN_TO_YOU => ['one' => 'nic se vám nestane'],
                self::REVEAL_POISON => ['one' => 'odhal jed'],
                self::HEAT_VISION => ['one' => 'vidění tepla'],
                self::GENIUS_LOCI => ['one' => 'genius loci'],
                self::WHERE_WAS_THAT => ['one' => 'kde to bylo?'],
                self::WHEN_DID_IT_HAPPEN => ['one' => 'kdy to bylo?'],
                self::MAGIC_VISION => ['one' => 'vidění magie'],
                self::LIAR => ['one' => 'lháři!'],
                self::FIND => ['one' => 'najdi'],
                self::HIDE_SLANDER => ['one' => 'skryj pomluvu'],
                self::CREATE_SLANDER => ['one' => 'stvoř pomluvu'],
                self::DECRYPT => ['one' => 'dešifruj'],
                self::COMPROMISE_CREATURE => ['one' => 'zkompromituj tvora'],
                self::SORE => ['one' => 'bolavé místo'],
                self::CHECK_INFORMATION => ['one' => 'prověř informaci'],
                // MENTAL SPELLS
                self::COLORS_AND_SHAPES => ['one' => 'barvy a tvary'],
                self::ENJOY_YOURSELF => ['one' => 'bav se dobře'],
                self::LOOK_AT_ME => ['one' => 'sem se mi dívej!'],
                self::NOTICE => ['one' => 'všimni si'],
                self::PATCH => ['one' => 'záplata'],
                self::NO_FEARS => ['one' => 'žádné strachy'],
                self::CAGE => ['one' => 'klec'],
                self::FOCUS => ['one' => 'koncentrace'],
                self::COME_HERE => ['one' => 'pojď sem!'],
                self::SLEEP_WELL => ['one' => 'spi sladce'],
                self::BASTION => ['one' => 'val'],
                self::CZARDAS => ['one' => 'čardáš'],
                self::IGNORE_ME => ['one' => 'nevšímejte si mě'],
                self::STAND_OUT => ['one' => 'vyčnívej'],
                self::BRACE_YOURSELF => ['one' => 'vzchop se!'],
                self::PAIN => ['one' => 'bolest'],
                self::WHAT_ARE_YOU_THINKING_ABOUT => ['one' => 'na co myslíš?'],
                self::BLINDNESS => ['one' => 'slepota'],
                self::FEAR => ['one' => 'strach'],
                self::BERSERK => ['one' => 'berserk'],
                self::I_AM_NOT_HERE_AT_ALL => ['one' => 'já tady vůbec nejsem'],
                self::DREAM => ['one' => 'sen'],
                self::DARKNESS => ['one' => 'temnota'],
                self::IDEA => ['one' => 'nápad'],
                self::CONCENTRATION => ['one' => 'soustředění'],
                self::PARTY => ['one' => 'večírek'],
                self::WALL_UP => ['one' => 'zazdi'],
                self::TRUST => ['one' => 'důvěra'],
                self::LUST => ['one' => 'chtíč'],
                self::YOU_WILL_NOT_SEE_MEE => ['one' => 'neuvidíš mne'],
                self::RESPECT => ['one' => 'respekt'],
                self::DAM => ['one' => 'hrázF'],
                self::PAST => ['one' => 'minulost'],
                self::NO_ONE_ANYWHERE => ['one' => 'nikde nikdo*'],
                self::FRIEND => ['one' => 'přítel'],
                self::DELIRIUM => ['one' => 'delirium'],
                self::PILLAR_OF_SALT => ['one' => 'solný sloup'],
                self::IRE => ['one' => 'zloba'],
                self::GO_AND_DO_AS_I_ASK => ['one' => 'jdi a konej, jak žádám'],
                self::PHANTASM => ['one' => 'přelud'],
                // VITAL
                self::QUINT_OF_SENSES => ['one' => 'Kvinta smyslů'],
                self::TORTURE => ['one' => 'Muka'],
                self::LUBBER => ['one' => 'Nemotora'],
                self::STUN => ['one' => 'Omráčení'],
                self::HICCUP => ['one' => 'Škytavka'],
                self::FIZZLE_OUT => ['one' => 'Uvadni'],
                self::TRIP_UP => ['one' => 'Zakopni'],
                self::CAROUSEL => ['one' => 'Kolotoč'],
                self::CADAVERIC_SLEEP => ['one' => 'Mrtvolný spánek'],
                self::DYE_SKIN => ['one' => 'Obarvi kůži'],
                self::EXHAUST => ['one' => 'Vyčerpej'],
                self::CHANGE_FACE => ['one' => 'Změň tvář'],
                self::BARBER => ['one' => 'Lazebník'],
                self::LET_WOUNDS_BLEED => ['one' => 'Nech rány krvácet'],
                self::SUMMON_UNDEADS => ['one' => 'Přivolej nemrtvé'],
                self::STRAITJACKET => ['one' => 'Svěrací kazajka'],
                self::TEAR_OF_EARS => ['one' => 'Urvi uši'],
                self::PIERCE_EYES => ['one' => 'Vypíchni oči'],
                self::CUT_OUT_TONGUE => ['one' => 'Vyřízni jazyk'],
                self::CRIPPLE_HAND => ['one' => 'Zchrom ruku'],
                self::BLEED_FROM_EYES => ['one' => 'Krvácej z očí'],
                self::METAMORPHOSIS_INTO => ['one' => 'Metamorfóza v ...'],
                self::VOODOO => ['one' => 'Voodoo'],
                self::BECOME_TRANSLUCENT => ['one' => 'Zprůsvitnění'],
                self::TOUCH_OF_DEATH => ['one' => 'Dotek smrti'],
                self::BONE_BREAKER => ['one' => 'Lamač kostí'],
                self::NIMBLENESS => ['one' => 'Mrštnost'],
                self::BRING_ON_ALL_ILLNESS => ['one' => 'Přivoď nemoc'],
                self::INSOMNIA => ['one' => 'Insomnie'],
                self::CONTROL_ZOMBIE => ['one' => 'Ovládni zombíka'],
                self::LEAKAGE => ['one' => 'Průsak'],
                self::SNAIL_SHELL => ['one' => 'Šnečí krunýř'],
                self::UNDEAD_SERVANT => ['one' => 'Nemrtvý služebník'],
                self::TIT_FOR_TAT => ['one' => 'Oko za oko, zub za zub'],
                self::DRAIN_VITALITY => ['one' => 'Vysaj sílu'],
                self::CONTROL_OVER_GHOSTS => ['one' => 'Moc nad duchy'],
                self::WITHER_AWAY => ['one' => 'Uchřadni'],
                self::BACK_TO_THE_GRAVE => ['one' => 'Vrať se do hrobu'],
                self::ANIMATE_UNDEAD => ['one' => 'Oživ nemrtvého'],
                self::GLANCE_AT_WORLD_BY_EYES => ['one' => 'Pohlédni na svět očima...'],
            ],
        ];
    }

}