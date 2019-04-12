<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Demons;

use DrdPlus\Codes\Theurgist\DemonTraitCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use DrdPlus\Tables\Theurgist\Spells\ToFlatArrayTrait;

/**
 * @link https://theurg.drdplus.info/#seznam_demonu_dle_skupin_a_sfer
 */
class DemonTraitsTable extends AbstractFileTable
{
    use ToFlatArrayTrait;

    /**
     * @var Tables
     */
    private $tables;

    public function __construct(Tables $tables)
    {
        $this->tables = $tables;
    }

    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/demon_traits.csv';
    }

    public const REALM = 'realm';
    public const REALMS_AFFECTION = 'realms_affection';

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::REALM => self::POSITIVE_INTEGER,
            self::REALMS_AFFECTION => self::ARRAY,
        ];
    }

    public const TRAIT = 'trait';

    protected function getRowsHeader(): array
    {
        return [self::TRAIT];
    }

    public function getRealm(DemonTraitCode $demonTraitCode): Realm
    {
        return new Realm($this->getValue($demonTraitCode, self::REALM));
    }

    public function getRealmsAffection(DemonTraitCode $demonTraitCode): RealmsAffection
    {
        return new RealmsAffection($this->getValue($demonTraitCode, self::REALMS_AFFECTION));
    }

}