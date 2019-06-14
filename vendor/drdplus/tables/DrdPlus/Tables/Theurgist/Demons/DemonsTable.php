<?php declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Demons;

use DrdPlus\Codes\Theurgist\DemonBodyCode;
use DrdPlus\Codes\Theurgist\DemonCode;
use DrdPlus\Codes\Theurgist\DemonKindCode;
use DrdPlus\Codes\Theurgist\DemonTraitCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonActivationDuration;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonAgility;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonArea;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonArmor;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonCapacity;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonEndurance;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonInvisibility;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonKnack;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonQuality;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonRadius;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonStrength;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonWill;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Difficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Evocation;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
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
    public const DEMON_BODY_CODE = 'demon_body_code';
    public const DEMON_KIND_CODE = 'demon_kind_code';
    public const REALMS_AFFECTION = 'realms_affection';
    public const DIFFICULTY = 'difficulty';
    public const DEMON_TRAITS = 'demon_traits';
    public const DEMON_CAPACITY = 'demon_capacity';
    public const DEMON_ENDURANCE = 'demon_endurance';
    public const SPELL_SPEED = 'spell_speed';
    public const DEMON_QUALITY = 'demon_quality';
    public const DEMON_ACTIVATION_DURATION = 'demon_activation_duration';
    public const DEMON_RADIUS = 'demon_radius';
    public const DEMON_STRENGTH = 'demon_strength';
    public const DEMON_AGILITY = 'demon_agility';
    public const DEMON_KNACK = 'demon_knack';
    public const DEMON_WILL = 'demon_will';
    public const DEMON_ARMOR = 'demon_armor';
    public const DEMON_INVISIBILITY = 'demon_invisibility';
    public const DEMON_AREA = 'demon_area';

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::REALM => self::STRING,
            self::EVOCATION => self::ARRAY,
            self::DEMON_BODY_CODE => self::STRING,
            self::DEMON_KIND_CODE => self::STRING,
            self::REALMS_AFFECTION => self::ARRAY,
            self::DIFFICULTY => self::ARRAY,
            self::DEMON_TRAITS => self::ARRAY,
            self::DEMON_CAPACITY => self::ARRAY,
            self::DEMON_ENDURANCE => self::ARRAY,
            self::SPELL_SPEED => self::ARRAY,
            self::DEMON_QUALITY => self::ARRAY,
            self::DEMON_ACTIVATION_DURATION => self::ARRAY,
            self::DEMON_RADIUS => self::ARRAY,
            self::DEMON_STRENGTH => self::ARRAY,
            self::DEMON_AGILITY => self::ARRAY,
            self::DEMON_KNACK => self::ARRAY,
            self::DEMON_WILL => self::ARRAY,
            self::DEMON_ARMOR => self::ARRAY,
            self::DEMON_INVISIBILITY => self::ARRAY,
            self::DEMON_AREA => self::ARRAY,
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
            function (DemonTraitCode $demonTraitCode) {
                return new DemonTrait($demonTraitCode, $this->tables);
            },
            $this->getDemonTraitCodes($demonCode)
        );
    }

    /**
     * @param DemonCode $demonCode
     * @return array|DemonTraitCode[]
     */
    public function getDemonTraitCodes(DemonCode $demonCode): array
    {
        return array_map(
            function (string $demonTraitValue) {
                return DemonTraitCode::getIt($demonTraitValue);
            },
            $this->getValue($demonCode, self::DEMON_TRAITS)
        );
    }

    public function getRealmsAffection(DemonCode $demonCode): RealmsAffection
    {
        return new RealmsAffection($this->getValue($demonCode, self::REALMS_AFFECTION));
    }

    public function getDemonBodyCode(DemonCode $demonCode): DemonBodyCode
    {
        return DemonBodyCode::getIt($this->getValue($demonCode, self::DEMON_BODY_CODE));
    }

    public function getDemonKindCode(DemonCode $demonCode): DemonKindCode
    {
        return DemonKindCode::getIt($this->getValue($demonCode, self::DEMON_KIND_CODE));
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

    public function getDemonQuality(DemonCode $demonCode): ?DemonQuality
    {
        $value = $this->getValue($demonCode, self::DEMON_QUALITY);
        if ($value === []) {
            return null;
        }
        return new DemonQuality($value, $this->tables);
    }

    public function getDemonActivationDuration(DemonCode $demonCode): ?DemonActivationDuration
    {
        $value = $this->getValue($demonCode, self::DEMON_ACTIVATION_DURATION);
        if ($value === []) {
            return null;
        }
        return new DemonActivationDuration($value, $this->tables);
    }

    public function getDemonRadius(DemonCode $demonCode): ?DemonRadius
    {
        $value = $this->getValue($demonCode, self::DEMON_RADIUS);
        if ($value === []) {
            return null;
        }
        return new DemonRadius($value, $this->tables);
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

    public function getDemonWill(DemonCode $demonCode): DemonWill
    {
        return new DemonWill($this->getValue($demonCode, self::DEMON_WILL), $this->tables);
    }

    public function getDemonArmor(DemonCode $demonCode): ?DemonArmor
    {
        $value = $this->getValue($demonCode, self::DEMON_ARMOR);
        if ($value === []) {
            return null;
        }
        return new DemonArmor($value, $this->tables);
    }

    public function getDemonInvisibility(DemonCode $demonCode): ?DemonInvisibility
    {
        $value = $this->getValue($demonCode, self::DEMON_INVISIBILITY);
        if ($value === []) {
            return null;
        }
        return new DemonInvisibility($value, $this->tables);
    }

    public function getDemonArea(DemonCode $demonCode): ?DemonArea
    {
        $value = $this->getValue($demonCode, self::DEMON_AREA);
        if ($value === []) {
            return null;
        }
        return new DemonArea($value, $this->tables);
    }

}