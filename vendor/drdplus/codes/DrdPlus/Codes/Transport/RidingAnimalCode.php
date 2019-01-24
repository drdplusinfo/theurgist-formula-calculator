<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Transport;

use DrdPlus\Codes\Partials\TranslatableExtendableCode;

/**
 * @method static RidingAnimalCode getIt($codeValue)
 * @method static RidingAnimalCode findIt($codeValue)
 */
class RidingAnimalCode extends TranslatableExtendableCode
{
    public const HORSE = 'horse';
    public const DRAFT_HORSE = 'draft_horse';
    public const RIDING_HORSE = 'riding_horse';
    public const WAR_HORSE = 'war_horse';
    public const CAMEL = 'camel';
    public const ELEPHANT = 'elephant';
    public const YAK = 'yak';
    public const LAME = 'lame';
    public const DONKEY = 'donkey';
    public const PONY = 'pony';
    public const HINNY = 'hinny';
    public const MULE = 'mule';
    public const COW = 'cow';
    public const BULL = 'bull';
    public const UNICORN = 'unicorn';

    /**
     * @return array|string[]
     */
    protected static function getDefaultValues(): array
    {
        return [
            self::HORSE,
            self::DRAFT_HORSE,
            self::RIDING_HORSE,
            self::WAR_HORSE,
            self::CAMEL,
            self::ELEPHANT,
            self::YAK,
            self::LAME,
            self::DONKEY,
            self::PONY,
            self::HINNY,
            self::MULE,
            self::COW,
            self::BULL,
            self::UNICORN,
        ];
    }

    /**
     * @param string $newRidingAnimalCodeValue
     * @param array $translations
     * @return bool
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidLanguageCode
     * @throws \DrdPlus\Codes\Partials\Exceptions\UnknownTranslationPlural
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidTranslationFormat
     */
    public static function addNewRidingAnimalCode(string $newRidingAnimalCodeValue, array $translations): bool
    {
        return static::addNewCode($newRidingAnimalCodeValue, $translations);
    }

    protected function fetchTranslations(): array
    {
        return [
            'en' => [
                self::HORSE => [self::$ONE => 'horse', self::$FEW => 'horses', self::$MANY => 'horses'],
                self::DRAFT_HORSE => [self::$ONE => 'draft horse', self::$FEW => 'draft horses', self::$MANY => 'draft horses'],
                self::RIDING_HORSE => [self::$ONE => 'riding horse', self::$FEW => 'riding horses', self::$MANY => 'riding horses'],
                self::WAR_HORSE => [self::$ONE => 'war horse', self::$FEW => 'war horses', self::$MANY => 'war horses'],
                self::CAMEL => [self::$ONE => 'camel', self::$FEW => 'camels', self::$MANY => 'camels'],
                self::ELEPHANT => [self::$ONE => 'elephant', self::$FEW => 'elephants', self::$MANY => 'elephants'],
                self::YAK => [self::$ONE => 'yak', self::$FEW => 'yaks', self::$MANY => 'yaks'],
                self::LAME => [self::$ONE => 'lame', self::$FEW => 'lames', self::$MANY => 'lames'],
                self::DONKEY => [self::$ONE => 'donkey', self::$FEW => 'donkeys', self::$MANY => 'donkeys'],
                self::PONY => [self::$ONE => 'pony', self::$FEW => 'ponies', self::$MANY => 'ponies'],
                self::HINNY => [self::$ONE => 'hinny', self::$FEW => 'hinnies', self::$MANY => 'hinnies'],
                self::MULE => [self::$ONE => 'mule', self::$FEW => 'mules', self::$MANY => 'mules'],
                self::COW => [self::$ONE => 'cow', self::$FEW => 'cows', self::$MANY => 'cows'],
                self::BULL => [self::$ONE => 'bull', self::$FEW => 'bulls', self::$MANY => 'bulls'],
                self::UNICORN => [self::$ONE => 'unicorn', self::$FEW => 'unicorns', self::$MANY => 'unicorns'],
            ],
            'cs' => [
                self::HORSE => [self::$ONE => 'kůň', self::$FEW => 'koně', self::$MANY => 'koní'],
                self::DRAFT_HORSE => [self::$ONE => 'tažný kůň', self::$FEW => 'tažné koně', self::$MANY => 'tažných koní'],
                self::RIDING_HORSE => [self::$ONE => 'jezdecký kůň', self::$FEW => 'jezdečtí koně', self::$MANY => 'jezdeckých koní'],
                self::WAR_HORSE => [self::$ONE => 'válečný kůň', self::$FEW => 'válečné koně', self::$MANY => 'válečných koní'],
                self::CAMEL => [self::$ONE => 'velbloud', self::$FEW => 'velbloudi', self::$MANY => 'velbloudů'],
                self::ELEPHANT => [self::$ONE => 'slon', self::$FEW => 'sloni', self::$MANY => 'slonů'],
                self::YAK => [self::$ONE => 'jak', self::$FEW => 'jaci', self::$MANY => 'jaků'],
                self::LAME => [self::$ONE => 'lama', self::$FEW => 'lamy', self::$MANY => 'lam'],
                self::DONKEY => [self::$ONE => 'osel', self::$FEW => 'oslové', self::$MANY => 'oslů'],
                self::PONY => [self::$ONE => 'pony', self::$FEW => 'pony', self::$MANY => 'pony'],
                self::HINNY => [self::$ONE => 'mezek', self::$FEW => 'mezci', self::$MANY => 'mezků'],
                self::MULE => [self::$ONE => 'mula', self::$FEW => 'muly', self::$MANY => 'mul'],
                self::COW => [self::$ONE => 'kráva', self::$FEW => 'krávy', self::$MANY => 'krav'],
                self::BULL => [self::$ONE => 'býk', self::$FEW => 'býci', self::$MANY => 'býků'],
                self::UNICORN => [self::$ONE => 'jednorožec', self::$FEW => 'jednorožci', self::$MANY => 'jednorožců'],
            ],
        ];
    }

}