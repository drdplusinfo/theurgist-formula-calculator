<?php declare(strict_types=1);

namespace DrdPlus\Codes\Theurgist;

/**
 * @method static SpellTraitCode getIt($codeValue)
 * @method static SpellTraitCode findIt($codeValue)
 */
class SpellTraitCode extends AbstractTheurgistCode
{
    public const AFFECTING = 'affecting';
    public const INVISIBLE = 'invisible';
    public const SILENT = 'silent';
    public const ODORLESS = 'odorless';
    public const CYCLIC = 'cyclic';
    public const MEMORY = 'memory';
    public const DEFORMATION = 'deformation';
    public const UNIDIRECTIONAL = 'unidirectional';
    public const BIDIRECTIONAL = 'bidirectional';
    public const INACRID = 'inacrid';
    public const EVERY_SENSE = 'every_sense';
    public const SITUATIONAL = 'situational';
    public const SHAPESHIFT = 'shapeshift';
    public const STATE_CHANGE = 'state_change';
    public const NATURE_CHANGE = 'nature_change';
    public const NO_SMOKE = 'no_smoke';
    public const TRANSPARENCY = 'transparency';
    public const MULTIPLE_ENTRY = 'multiple_entry';
    public const OMNIPRESENT = 'omnipresent';
    public const ACTIVE = 'active';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::AFFECTING,
            self::INVISIBLE,
            self::SILENT,
            self::ODORLESS,
            self::CYCLIC,
            self::MEMORY,
            self::DEFORMATION,
            self::UNIDIRECTIONAL,
            self::BIDIRECTIONAL,
            self::INACRID,
            self::EVERY_SENSE,
            self::SITUATIONAL,
            self::SHAPESHIFT,
            self::STATE_CHANGE,
            self::NATURE_CHANGE,
            self::NO_SMOKE,
            self::TRANSPARENCY,
            self::MULTIPLE_ENTRY,
            self::OMNIPRESENT,
            self::ACTIVE,
        ];
    }

    protected function fetchTranslations(): array
    {
        return [
            'cs' => [
                'one' => [
                    self::AFFECTING => 'ovlivňující',
                    self::INVISIBLE => 'zneviditelňující',
                    self::SILENT => 'zneslyšitelňující',
                    self::ODORLESS => 'znevycítitelňující',
                    self::CYCLIC => 'cyklický',
                    self::MEMORY => 'paměť',
                    self::DEFORMATION => 'deformace',
                    self::UNIDIRECTIONAL => 'jednosměrná',
                    self::BIDIRECTIONAL => 'obousměrná',
                    self::INACRID => 'neštiplavý',
                    self::EVERY_SENSE => 'za každý smysl',
                    self::SITUATIONAL => 'situační',
                    self::SHAPESHIFT => 'změna tvaru',
                    self::STATE_CHANGE => 'změna skupenství',
                    self::NATURE_CHANGE => 'změna podstaty',
                    self::NO_SMOKE => 'bez dýmu',
                    self::TRANSPARENCY => 'průhlednost',
                    self::MULTIPLE_ENTRY => 'vícevstupný',
                    self::OMNIPRESENT => 'všudypřítomné',
                    self::ACTIVE => 'aktivní',
                ],
            ],
        ];
    }

}