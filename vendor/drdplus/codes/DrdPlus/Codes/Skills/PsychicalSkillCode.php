<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Skills;

/**
 * @method static PsychicalSkillCode getIt($codeValue)
 * @method static PsychicalSkillCode findIt($codeValue)
 */
class PsychicalSkillCode extends SkillCode
{

    // PSYCHICAL
    public const ASTRONOMY = 'astronomy';
    public const BOTANY = 'botany';
    public const ETIQUETTE_OF_GANGLAND = 'etiquette_of_gangland';
    public const FOREIGN_LANGUAGE = 'foreign_language';
    public const GEOGRAPHY_OF_A_COUNTRY = 'geography_of_a_country';
    public const HANDLING_WITH_MAGICAL_ITEMS = 'handling_with_magical_items';
    public const HISTORIOGRAPHY = 'historiography';
    public const KNOWLEDGE_OF_A_CITY = 'knowledge_of_a_city';
    public const KNOWLEDGE_OF_WORLD = 'knowledge_of_world';
    public const MAPS_DRAWING = 'maps_drawing';
    public const MYTHOLOGY = 'mythology';
    public const READING_AND_WRITING = 'reading_and_writing';
    public const SOCIAL_ETIQUETTE = 'social_etiquette';
    public const TECHNOLOGY = 'technology';
    public const THEOLOGY = 'theology';
    public const ZOOLOGY = 'zoology';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::ASTRONOMY,
            self::BOTANY,
            self::ETIQUETTE_OF_GANGLAND,
            self::FOREIGN_LANGUAGE,
            self::GEOGRAPHY_OF_A_COUNTRY,
            self::HANDLING_WITH_MAGICAL_ITEMS,
            self::HISTORIOGRAPHY,
            self::KNOWLEDGE_OF_A_CITY,
            self::KNOWLEDGE_OF_WORLD,
            self::MAPS_DRAWING,
            self::MYTHOLOGY,
            self::READING_AND_WRITING,
            self::SOCIAL_ETIQUETTE,
            self::TECHNOLOGY,
            self::THEOLOGY,
            self::ZOOLOGY,
        ];
    }

    protected function fetchTranslations(): array
    {
        return [
            'en' => [
                self::ASTRONOMY => [self::$ONE => 'astronomy'],
                self::BOTANY => [self::$ONE => 'botany'],
                self::ETIQUETTE_OF_GANGLAND => [self::$ONE => 'etiquette of gangland'],
                self::FOREIGN_LANGUAGE => [self::$ONE => 'foreign language'],
                self::GEOGRAPHY_OF_A_COUNTRY => [self::$ONE => 'geography of a country'],
                self::HANDLING_WITH_MAGICAL_ITEMS => [self::$ONE => 'handling with magical items'],
                self::HISTORIOGRAPHY => [self::$ONE => 'historiography'],
                self::KNOWLEDGE_OF_A_CITY => [self::$ONE => 'knowledge of a city'],
                self::KNOWLEDGE_OF_WORLD => [self::$ONE => 'knowledge of world'],
                self::MAPS_DRAWING => [self::$ONE => 'maps drawing'],
                self::MYTHOLOGY => [self::$ONE => 'mythology'],
                self::READING_AND_WRITING => [self::$ONE => 'reading and writing'],
                self::SOCIAL_ETIQUETTE => [self::$ONE => 'social etiquette'],
                self::TECHNOLOGY => [self::$ONE => 'technology'],
                self::THEOLOGY => [self::$ONE => 'theology'],
                self::ZOOLOGY => [self::$ONE => 'zoology'],
            ],
            'cs' => [
                self::ASTRONOMY => [self::$ONE => 'astronomie'],
                self::BOTANY => [self::$ONE => 'botanika'],
                self::ETIQUETTE_OF_GANGLAND => [self::$ONE => 'etiketa podstvětí'],
                self::FOREIGN_LANGUAGE => [self::$ONE => 'cizí jazyk'],
                self::GEOGRAPHY_OF_A_COUNTRY => [self::$ONE => 'zeměpis státu'],
                self::HANDLING_WITH_MAGICAL_ITEMS => [self::$ONE => 'zacházení s magickými předměty'],
                self::HISTORIOGRAPHY => [self::$ONE => 'dějeprava'],
                self::KNOWLEDGE_OF_A_CITY => [self::$ONE => 'znalost města'],
                self::KNOWLEDGE_OF_WORLD => [self::$ONE => 'znalost světa'],
                self::MAPS_DRAWING => [self::$ONE => 'kreslení map'],
                self::MYTHOLOGY => [self::$ONE => 'bájesloví'],
                self::READING_AND_WRITING => [self::$ONE => 'čtení a psaní'],
                self::SOCIAL_ETIQUETTE => [self::$ONE => 'společenská etiketa'],
                self::TECHNOLOGY => [self::$ONE => 'technologie'],
                self::THEOLOGY => [self::$ONE => 'teologie'],
                self::ZOOLOGY => [self::$ONE => 'zoologie'],
            ],
        ];
    }

}