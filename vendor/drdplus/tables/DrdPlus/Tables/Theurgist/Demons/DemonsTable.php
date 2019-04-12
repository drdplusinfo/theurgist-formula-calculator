<?php
namespace DrdPlus\Tables\Theurgist\Demons;

use DrdPlus\BaseProperties\Will;
use DrdPlus\Codes\Theurgist\DemonBodyCode;
use DrdPlus\Codes\Theurgist\DemonCode;
use DrdPlus\Codes\Theurgist\DemonKindCode;
use DrdPlus\Codes\Theurgist\DemonTraitCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonAgility;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonArmor;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonCapacity;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonEndurance;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonKnack;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonStrength;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Difficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Evocation;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Invisibility;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Quality;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellDuration;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellRadius;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;

/**
 * @link https://theurg.drdplus.info/#seznam_demonu_dle_skupin_a_sfer
 */
class DemonsTable extends AbstractFileTable
{
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
        return __DIR__ . '/data/demons.csv';
    }

    public const REALM = 'realm';
    public const EVOCATION = 'evocation';
    public const DEMON_BODY = 'demon_body';
    public const DEMON_KIND = 'demon_kind';
    public const REALMS_AFFECTION = 'realms_affection';
    public const WILL = 'will';
    public const DIFFICULTY = 'difficulty';
    public const DEMON_TRAITS = 'demon_traits';
    public const DEMON_CAPACITY = 'demon_capacity';
    public const DEMON_ENDURANCE = 'demon_endurance';
    public const SPELL_SPEED = 'spell_speed';
    public const QUALITY = 'quality';
    public const SPELL_DURATION = 'spell_duration';
    public const SPELL_RADIUS = 'spell_radius';
    public const DEMON_STRENGTH = 'demon_strength';
    public const DEMON_AGILITY = 'demon_agility';
    public const DEMON_KNACK = 'demon_knack';
    public const DEMON_ARMOR = 'demon_armor';
    public const INVISIBILITY = 'invisibility';

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::REALM => self::STRING,
            self::EVOCATION => self::ARRAY,
            self::DEMON_BODY => self::STRING,
            self::DEMON_KIND => self::STRING,
            self::REALMS_AFFECTION => self::ARRAY,
            self::WILL => self::INTEGER,
            self::DIFFICULTY => self::ARRAY,
            self::DEMON_TRAITS => self::ARRAY,
            self::DEMON_CAPACITY => self::ARRAY,
            self::DEMON_ENDURANCE => self::ARRAY,
            self::SPELL_SPEED => self::ARRAY,
            self::QUALITY => self::ARRAY,
            self::SPELL_DURATION => self::ARRAY,
            self::SPELL_RADIUS => self::ARRAY,
            self::DEMON_STRENGTH => self::ARRAY,
            self::DEMON_AGILITY => self::ARRAY,
            self::DEMON_KNACK => self::ARRAY,
            self::DEMON_ARMOR => self::ARRAY,
            self::INVISIBILITY => self::ARRAY,

        ];
    }

    public const DEMON = 'demon';

    protected function getRowsHeader(): array
    {
        return [self::DEMON];
    }

    public function getRealm(DemonCode $demonCode): Realm
    {
        return new Realm($this->getValue($demonCode, self::REALM));
    }

    public function getEvocation(DemonCode $demonCode): Evocation
    {
        return new Evocation($this->getValue($demonCode, self::EVOCATION), $this->tables);
    }

    public function getDifficulty(DemonCode $demonCode): Difficulty
    {
        return new Difficulty($this->getValue($demonCode, self::DIFFICULTY));
    }

    /**
     * @param DemonCode $demonCode
     * @return array|DemonTrait[]
     */
    public function getDemonTraits(DemonCode $demonCode): array
    {
        return array_map(
            function (string $demonTraitValue) {
                $demonTraitCode = DemonTraitCode::getIt($demonTraitValue);
                return new DemonTrait($demonTraitCode, $this->tables);
            },
            $this->getValue($demonCode, self::DEMON_TRAITS)
        );
    }

    public function getRealmsAffection(DemonCode $demonCode): RealmsAffection
    {
        return new RealmsAffection($this->getValue($demonCode, self::REALMS_AFFECTION));
    }

    public function getDemonBody(DemonCode $demonCode): DemonBodyCode
    {
        return DemonBodyCode::getIt($this->getValue($demonCode, self::DEMON_BODY));
    }

    public function getDemonKind(DemonCode $demonCode): DemonKindCode
    {
        return DemonKindCode::getIt($this->getValue($demonCode, self::DEMON_KIND));
    }

    public function getWill(DemonCode $demonCode): Will
    {
        return Will::getIt($this->getValue($demonCode, self::WILL));
    }

    public function getDemonCapacity(DemonCode $demonCode): ?DemonCapacity
    {
        $value = $this->getValue($demonCode, self::DEMON_CAPACITY);
        if ($value === []) {
            return null;
        }
        return new DemonCapacity($value, $this->tables);
    }

    public function getDemonEndurance(DemonCode $demonCode): ?DemonEndurance
    {
        $value = $this->getValue($demonCode, self::DEMON_ENDURANCE);
        if ($value === []) {
            return null;
        }
        return new DemonEndurance($value, $this->tables);
    }

    public function getSpellSpeed(DemonCode $demonCode): ?SpellSpeed
    {
        $value = $this->getValue($demonCode, self::SPELL_SPEED);
        if ($value === []) {
            return null;
        }
        return new SpellSpeed($value, $this->tables);
    }

    public function getQuality(DemonCode $demonCode): ?Quality
    {
        $value = $this->getValue($demonCode, self::QUALITY);
        if ($value === []) {
            return null;
        }
        return new Quality($value, $this->tables);
    }

    public function getSpellDuration(DemonCode $demonCode): ?SpellDuration
    {
        $value = $this->getValue($demonCode, self::SPELL_DURATION);
        if ($value === []) {
            return null;
        }
        return new SpellDuration($value, $this->tables);
    }

    public function getSpellRadius(DemonCode $demonCode): ?SpellRadius
    {
        $value = $this->getValue($demonCode, self::SPELL_RADIUS);
        if ($value === []) {
            return null;
        }
        return new SpellRadius($value, $this->tables);
    }

    public function getDemonStrength(DemonCode $demonCode): ?DemonStrength
    {
        $value = $this->getValue($demonCode, self::DEMON_STRENGTH);
        if ($value === []) {
            return null;
        }
        return new DemonStrength($value, $this->tables);
    }

    public function getDemonAgility(DemonCode $demonCode): ?DemonAgility
    {
        $value = $this->getValue($demonCode, self::DEMON_AGILITY);
        if ($value === []) {
            return null;
        }
        return new DemonAgility($value, $this->tables);
    }

    public function getDemonKnack(DemonCode $demonCode): ?DemonKnack
    {
        $value = $this->getValue($demonCode, self::DEMON_KNACK);
        if ($value === []) {
            return null;
        }
        return new DemonKnack($value, $this->tables);
    }

    public function getDemonArmor(DemonCode $demonCode): ?DemonArmor
    {
        $value = $this->getValue($demonCode, self::DEMON_ARMOR);
        if ($value === []) {
            return null;
        }
        return new DemonArmor($value, $this->tables);
    }

    public function getInvisibility(DemonCode $demonCode): ?Invisibility
    {
        $value = $this->getValue($demonCode, self::INVISIBILITY);
        if ($value === []) {
            return null;
        }
        return new Invisibility($value, $this->tables);
    }

}