<?php declare(strict_types=1); 

namespace DrdPlus\Codes\Properties;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static PropertyCode getIt($codeValue)
 * @method static PropertyCode findIt($codeValue)
 */
class PropertyCode extends TranslatableCode
{
    public const STRENGTH = 'strength';
    public const AGILITY = 'agility';
    public const KNACK = 'knack';
    public const WILL = 'will';
    public const INTELLIGENCE = 'intelligence';
    public const CHARISMA = 'charisma';

    /**
     * @return array|string[]
     */
    public static function getBasePropertyPossibleValues(): array
    {
        return [
            self::STRENGTH,
            self::AGILITY,
            self::KNACK,
            self::WILL,
            self::INTELLIGENCE,
            self::CHARISMA,
        ];
    }

    // body
    public const AGE = 'age';
    public const HEIGHT_IN_CM = 'height_in_cm';
    public const HEIGHT = 'height';
    public const BODY_WEIGHT_IN_KG = 'body_weight_in_kg';
    public const BODY_WEIGHT = 'body_weight';
    public const SIZE = 'size';

    /**
     * @return array|string[]
     */
    public static function getBodyPropertyPossibleValues(): array
    {
        return [
            self::AGE,
            self::HEIGHT_IN_CM,
            self::HEIGHT,
            self::BODY_WEIGHT_IN_KG,
            self::BODY_WEIGHT,
            self::SIZE,
        ];
    }

    // derived
    public const BEAUTY = 'beauty';
    public const DANGEROUSNESS = 'dangerousness';
    public const DIGNITY = 'dignity';
    public const ENDURANCE = 'endurance';
    public const FATIGUE_BOUNDARY = 'fatigue_boundary';
    public const SENSES = 'senses';
    public const SPEED = 'speed';
    public const TOUGHNESS = 'toughness';
    public const WOUND_BOUNDARY = 'wound_boundary';
    public const MOVEMENT_SPEED = 'movement_speed';
    public const MAXIMAL_LOAD = 'maximal_load';

    /**
     * @return array|string[]
     */
    public static function getDerivedPropertyPossibleValues(): array
    {
        return [
            self::BEAUTY,
            self::DANGEROUSNESS,
            self::DIGNITY,
            self::ENDURANCE,
            self::FATIGUE_BOUNDARY,
            self::SENSES,
            self::SPEED,
            self::TOUGHNESS,
            self::WOUND_BOUNDARY,
            self::MOVEMENT_SPEED,
            self::MAXIMAL_LOAD,
        ];
    }

    // native
    public const REMARKABLE_SENSE = 'remarkable_sense';
    public const INFRAVISION = 'infravision';
    public const NATIVE_REGENERATION = 'native_regeneration';

    /**
     * @return array|string[]
     */
    public static function getNativePropertyPossibleValues(): array
    {
        return [
            self::INFRAVISION,
            self::NATIVE_REGENERATION,
            self::REMARKABLE_SENSE,
        ];
    }

    // remarkable senses
    public const HEARING = RemarkableSenseCode::HEARING;
    public const SIGHT = RemarkableSenseCode::SIGHT;
    public const SMELL = RemarkableSenseCode::SMELL;
    public const TASTE = RemarkableSenseCode::TASTE;
    public const TOUCH = RemarkableSenseCode::TOUCH;

    /**
     * @return array|string[]
     */
    public static function getRemarkableSensePropertyPossibleValues(): array
    {
        return RemarkableSenseCode::getPossibleValues();
    }

    // restrictions
    public const REQUIRES_DM_AGREEMENT = 'requires_dm_agreement';

    /**
     * @return array|string[]
     */
    public static function getRestrictionPropertyPossibleValues(): array
    {
        return [
            self::REQUIRES_DM_AGREEMENT,
        ];
    }

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return \array_merge(
            self::getBasePropertyPossibleValues(),
            self::getBodyPropertyPossibleValues(),
            self::getDerivedPropertyPossibleValues(),
            self::getNativePropertyPossibleValues(),
            self::getRemarkableSensePropertyPossibleValues(),
            self::getRestrictionPropertyPossibleValues()
        );
    }

    protected function fetchTranslations(): array
    {
        return [
            'en' => [
                self::STRENGTH => [self::$ONE => 'strength', self::$FEW => 'strengths', self::$MANY => 'strengths'],
                self::AGILITY => [self::$ONE => 'agility', self::$FEW => 'agilities', self::$MANY => 'agilities'],
                self::KNACK => [self::$ONE => 'knack', self::$FEW => 'knacks', self::$MANY => 'knacks'],
                self::WILL => [self::$ONE => 'will', self::$FEW => 'wills', self::$MANY => 'wills'],
                self::INTELLIGENCE => [self::$ONE => 'intelligence', self::$FEW => 'intelligences', self::$MANY => 'intelligences'],
                self::CHARISMA => [self::$ONE => 'charisma', self::$FEW => 'charismata', self::$MANY => 'charismata'],
                self::AGE => [self::$ONE => 'age', self::$FEW => 'ages', self::$MANY => 'ages'],
                self::HEIGHT_IN_CM => [self::$ONE => 'height in cm', self::$FEW => 'heights in cm', self::$MANY => 'heights in cm'],
                self::HEIGHT => [self::$ONE => 'height', self::$FEW => 'heights', self::$MANY => 'heights'],
                self::BODY_WEIGHT_IN_KG => [self::$ONE => 'body weight in kg', self::$FEW => 'body weights in kg', self::$MANY => 'body weights in kg'],
                self::BODY_WEIGHT => [self::$ONE => 'body weight', self::$FEW => 'body weights', self::$MANY => 'body weights'],
                self::SIZE => [self::$ONE => 'size', self::$FEW => 'sizes', self::$MANY => 'sizes'],
                self::BEAUTY => [self::$ONE => 'beauty', self::$FEW => 'beauties', self::$MANY => 'beauties'],
                self::DANGEROUSNESS => [self::$ONE => 'dangerousness', self::$FEW => 'dangerousnesses', self::$MANY => 'dangerousnesses'],
                self::DIGNITY => [self::$ONE => 'dignity', self::$FEW => 'dignities', self::$MANY => 'dignities'],
                self::ENDURANCE => [self::$ONE => 'endurance', self::$FEW => 'endurances', self::$MANY => 'endurances'],
                self::FATIGUE_BOUNDARY => [self::$ONE => 'fatigue boundary', self::$FEW => 'fatigue boundaries', self::$MANY => 'fatigue boundaries'],
                self::SENSES => [self::$ONE => 'senses', self::$FEW => 'senses', self::$MANY => 'senses'],
                self::SPEED => [self::$ONE => 'speed', self::$FEW => 'speeds', self::$MANY => 'speeds'],
                self::TOUGHNESS => [self::$ONE => 'toughness', self::$FEW => 'toughnesses', self::$MANY => 'toughnesses'],
                self::WOUND_BOUNDARY => [self::$ONE => 'wound boundary', self::$FEW => 'wound boundaries', self::$MANY => 'wound boundaries'],
                self::MOVEMENT_SPEED => [self::$ONE => 'movement speed', self::$FEW => 'movement speeds', self::$MANY => 'movement speeds'],
                self::MAXIMAL_LOAD => [self::$ONE => 'maximal load', self::$FEW => 'maximal loads', self::$MANY => 'maximal loads'],
                self::INFRAVISION => [self::$ONE => 'infravision', self::$FEW => 'infravisions', self::$MANY => 'infravisions'],
                self::NATIVE_REGENERATION => [self::$ONE => 'native regeneration', self::$FEW => 'native regenerations', self::$MANY => 'native regenerations'],
                self::REMARKABLE_SENSE => [self::$ONE => 'remarkable sense', self::$FEW => 'remarkable senses', self::$MANY => 'remarkable senses'],
                self::HEARING => [self::$ONE => 'hearing', self::$FEW => 'hearings', self::$MANY => 'hearings'],
                self::SIGHT => [self::$ONE => 'sight', self::$FEW => 'sights', self::$MANY => 'sights'],
                self::SMELL => [self::$ONE => 'smell', self::$FEW => 'smells', self::$MANY => 'smells'],
                self::TASTE => [self::$ONE => 'taste', self::$FEW => 'tastes', self::$MANY => 'tastes'],
                self::TOUCH => [self::$ONE => 'touch', self::$FEW => 'touches', self::$MANY => 'touches'],
                self::REQUIRES_DM_AGREEMENT => [self::$ONE => 'requires DM agreement', self::$FEW => 'requires DM agreements', self::$MANY => 'requires DM agreements'],
            ],
            'cs' => [
                self::STRENGTH => [self::$ONE => 'síla', self::$FEW => 'síly', self::$MANY => 'sil'],
                self::AGILITY => [self::$ONE => 'obratnost', self::$FEW => 'obratnosti', self::$MANY => 'obratností'],
                self::KNACK => [self::$ONE => 'zručnost', self::$FEW => 'zručnosti', self::$MANY => 'zručností'],
                self::WILL => [self::$ONE => 'vůle', self::$FEW => 'vůle', self::$MANY => 'vůlí'],
                self::INTELLIGENCE => [self::$ONE => 'inteligence', self::$FEW => 'inteligence', self::$MANY => 'inteligencí'],
                self::CHARISMA => [self::$ONE => 'charisma', self::$FEW => 'charismy', self::$MANY => 'charisem'],
                self::AGE => [self::$ONE => 'věk', self::$FEW => 'věky', self::$MANY => 'věků'],
                self::HEIGHT_IN_CM => [self::$ONE => 'výška v cm', self::$FEW => 'výšky cm', self::$MANY => 'výšek v cm'],
                self::HEIGHT => [self::$ONE => 'výška', self::$FEW => 'výšky', self::$MANY => 'výšek'],
                self::BODY_WEIGHT_IN_KG => [self::$ONE => 'váha v kg', self::$FEW => 'váhy kg', self::$MANY => 'váh v kg'],
                self::BODY_WEIGHT => [self::$ONE => 'váha', self::$FEW => 'váhy', self::$MANY => 'váh'],
                self::SIZE => [self::$ONE => 'velikost', self::$FEW => 'velikosti', self::$MANY => 'velikostí'],
                self::BEAUTY => [self::$ONE => 'krása', self::$FEW => 'krásy', self::$MANY => 'krás'],
                self::DANGEROUSNESS => [self::$ONE => 'nebezpečnost', self::$FEW => 'nebezpečnosti', self::$MANY => 'nebezpečností'],
                self::DIGNITY => [self::$ONE => 'důstojnost', self::$FEW => 'důstojnosti', self::$MANY => 'důstojností'],
                self::ENDURANCE => [self::$ONE => 'výdrž', self::$FEW => 'výdrže', self::$MANY => 'výdrží'],
                self::FATIGUE_BOUNDARY => [self::$ONE => 'mez únavy', self::$FEW => 'meze únavy', self::$MANY => 'mezí únavy'],
                self::SENSES => [self::$ONE => 'smysly', self::$FEW => 'smysly', self::$MANY => 'smysly'],
                self::SPEED => [self::$ONE => 'rychlost', self::$FEW => 'rychlosti', self::$MANY => 'rychlostí'],
                self::TOUGHNESS => [self::$ONE => 'odolnost', self::$FEW => 'odolnosti', self::$MANY => 'odolností'],
                self::WOUND_BOUNDARY => [self::$ONE => 'mez zranění', self::$FEW => 'meze zranění', self::$MANY => 'mezí zranění'],
                self::MOVEMENT_SPEED => [self::$ONE => 'pohybová rychlost', self::$FEW => 'pohybové rychlosti', self::$MANY => 'pohybových rychlostí'],
                self::MAXIMAL_LOAD => [self::$ONE => 'maximální naložení', self::$FEW => 'maximální naložení', self::$MANY => 'maximálních naložení'],
                self::INFRAVISION => [self::$ONE => 'infravize', self::$FEW => 'infravize', self::$MANY => 'infravizí'],
                self::NATIVE_REGENERATION => [self::$ONE => 'přirozená regenerace', self::$FEW => 'přirozené regenerace', self::$MANY => 'přirozených regenerací'],
                self::REMARKABLE_SENSE => [self::$ONE => 'význačný smysl', self::$FEW => 'význačné smysly', self::$MANY => 'význačných smyslů'],
                self::HEARING => [self::$ONE => 'sluch', self::$FEW => 'sluchy', self::$MANY => 'sluchů'],
                self::SIGHT => [self::$ONE => 'zrak', self::$FEW => 'zraky', self::$MANY => 'zraků'],
                self::SMELL => [self::$ONE => 'čich', self::$FEW => 'čichy', self::$MANY => 'čich'],
                self::TASTE => [self::$ONE => 'chuť', self::$FEW => 'chutě', self::$MANY => 'chutí'],
                self::TOUCH => [self::$ONE => 'hmat', self::$FEW => 'hmaty', self::$MANY => 'hmatů'],
                self::REQUIRES_DM_AGREEMENT => [self::$ONE => 'vyžaduje souhlas PJ', self::$FEW => 'vyžadují souhlas PJ', self::$MANY => 'vyžaduje souhlas PJ'],
            ],
        ];
    }

}