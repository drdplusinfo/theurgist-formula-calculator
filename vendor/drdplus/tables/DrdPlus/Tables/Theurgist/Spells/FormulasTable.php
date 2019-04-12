<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells;

use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use DrdPlus\Codes\Theurgist\FormCode;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ProfileCode;
use DrdPlus\Codes\Theurgist\SpellTraitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\CastingRounds;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellAttack;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellBrightness;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Evocation;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DetailLevel;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Difficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellDuration;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\EpicenterShift;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellPower;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellRadius;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SizeChange;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;

/**
 * @link https://theurg.drdplus.info/#tabulka_formuli
 */
class FormulasTable extends AbstractFileTable
{
    /**
     * @var Tables
     */
    private $tables;

    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/formulas.csv';
    }

    public const REALM = 'realm';
    public const REALMS_AFFECTION = 'realms_affection';
    public const EVOCATION = 'evocation';
    public const DIFFICULTY = 'difficulty';
    public const SPELL_RADIUS = 'spell_radius';
    public const SPELL_DURATION = 'spell_duration';
    public const SPELL_POWER = 'spell_power';
    public const SPELL_ATTACK = 'spell_attack';
    public const SIZE_CHANGE = 'size_change';
    public const DETAIL_LEVEL = 'detail_level';
    public const SPELL_BRIGHTNESS = 'spell_brightness';
    public const SPELL_SPEED = 'spell_speed';
    public const EPICENTER_SHIFT = 'epicenter_shift';
    public const FORMS = 'forms';
    public const SPELL_TRAITS = 'spell_traits';
    public const PROFILES = 'profiles';
    public const MODIFIERS = 'modifiers';

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::REALM => self::POSITIVE_INTEGER,
            self::REALMS_AFFECTION => self::ARRAY,
            self::EVOCATION => self::ARRAY,
            self::DIFFICULTY => self::ARRAY,
            self::SPELL_RADIUS => self::ARRAY,
            self::SPELL_DURATION => self::ARRAY,
            self::SPELL_POWER => self::ARRAY,
            self::SPELL_ATTACK => self::ARRAY,
            self::SIZE_CHANGE => self::ARRAY,
            self::DETAIL_LEVEL => self::ARRAY,
            self::SPELL_BRIGHTNESS => self::ARRAY,
            self::SPELL_SPEED => self::ARRAY,
            self::EPICENTER_SHIFT => self::ARRAY,
            self::FORMS => self::ARRAY,
            self::SPELL_TRAITS => self::ARRAY,
            self::PROFILES => self::ARRAY,
            self::MODIFIERS => self::ARRAY,
        ];
    }

    public const FORMULA = 'formula';

    public function __construct(Tables $tables)
    {
        $this->tables = $tables;
    }

    protected function getRowsHeader(): array
    {
        return [
            self::FORMULA,
        ];
    }

    public function getRealm(FormulaCode $formulaCode): Realm
    {
        return new Realm($this->getValue($formulaCode, self::REALM));
    }

    public function getRealmsAffection(FormulaCode $formulaCode): RealmsAffection
    {
        return new RealmsAffection($this->getValue($formulaCode, self::REALMS_AFFECTION));
    }

    /**
     * Time needed to invoke (assemble) a spell. Gives time bonus value in fact.
     *
     * @param FormulaCode $formulaCode
     * @return Evocation
     */
    public function getEvocation(FormulaCode $formulaCode): Evocation
    {
        return new Evocation($this->getValue($formulaCode, self::EVOCATION), $this->tables);
    }

    /**
     * Gives time in fact.
     * Currently every unmodified formula can be casted in one round.
     *
     * @param FormulaCode $formulaCode
     * @return CastingRounds
     */
    public function getCastingRounds(/** @noinspection PhpUnusedParameterInspection to keep same interface with others */
        FormulaCode $formulaCode): CastingRounds
    {
        return new CastingRounds([1, 0], $this->tables);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Difficulty
     */
    public function getDifficulty(FormulaCode $formulaCode): Difficulty
    {
        return new Difficulty($this->getValue($formulaCode, self::DIFFICULTY));
    }

    /**
     * @param FormulaCode $formulaCode
     * @return SpellRadius|null
     */
    public function getSpellRadius(FormulaCode $formulaCode): ?SpellRadius
    {
        $radiusValues = $this->getValue($formulaCode, self::SPELL_RADIUS);
        if (!$radiusValues) {
            return null;
        }

        return new SpellRadius($radiusValues, $this->tables);
    }

    public function getSpellDuration(FormulaCode $formulaCode): SpellDuration
    {
        return new SpellDuration($this->getValue($formulaCode, self::SPELL_DURATION), $this->tables);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return SpellPower|null
     */
    public function getSpellPower(FormulaCode $formulaCode): ?SpellPower
    {
        $powerValues = $this->getValue($formulaCode, self::SPELL_POWER);
        if (!$powerValues) {
            return null;
        }

        return new SpellPower($powerValues, $this->tables);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return SpellAttack|null
     */
    public function getSpellAttack(FormulaCode $formulaCode): ?SpellAttack
    {
        $attackValues = $this->getValue($formulaCode, self::SPELL_ATTACK);
        if (!$attackValues) {
            return null;
        }

        return new SpellAttack($attackValues, $this->tables);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return SizeChange|null
     */
    public function getSizeChange(FormulaCode $formulaCode): ?SizeChange
    {
        $sizeChangeValues = $this->getValue($formulaCode, self::SIZE_CHANGE);
        if (!$sizeChangeValues) {
            return null;
        }

        return new SizeChange($sizeChangeValues, $this->tables);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return DetailLevel|null
     */
    public function getDetailLevel(FormulaCode $formulaCode): ?DetailLevel
    {
        $detailLevelValues = $this->getValue($formulaCode, self::DETAIL_LEVEL);
        if (!$detailLevelValues) {
            return null;
        }

        return new DetailLevel($detailLevelValues, $this->tables);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return SpellBrightness|null
     */
    public function getSpellBrightness(FormulaCode $formulaCode): ?SpellBrightness
    {
        $brightnessValues = $this->getValue($formulaCode, self::SPELL_BRIGHTNESS);
        if (!$brightnessValues) {
            return null;
        }

        return new SpellBrightness($brightnessValues, $this->tables);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return SpellSpeed|null
     */
    public function getSpellSpeed(FormulaCode $formulaCode): ?SpellSpeed
    {
        $speedValues = $this->getValue($formulaCode, self::SPELL_SPEED);
        if (!$speedValues) {
            return null;
        }

        return new SpellSpeed($speedValues, $this->tables);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return EpicenterShift|null
     */
    public function getEpicenterShift(FormulaCode $formulaCode): ?EpicenterShift
    {
        $epicenterShift = $this->getValue($formulaCode, self::EPICENTER_SHIFT);
        if (!$epicenterShift) {
            return null;
        }

        return new EpicenterShift($epicenterShift, $this->tables);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return array|FormCode[]
     */
    public function getForms(FormulaCode $formulaCode): array
    {
        return array_map(
            function (string $formValue) {
                return FormCode::getIt($formValue);
            },
            $this->getValue($formulaCode, self::FORMS)
        );
    }

    /**
     * @param FormulaCode $formulaCode
     * @return array|SpellTrait[]
     */
    public function getSpellTraits(FormulaCode $formulaCode): array
    {
        return array_map(
            function (string $spellTraitValue) {
                $spellTraitCode = SpellTraitCode::getIt($spellTraitValue);
                return new SpellTrait($spellTraitCode, $this->tables);
            },
            $this->getValue($formulaCode, self::SPELL_TRAITS)
        );
    }

    /**
     * @param FormulaCode $formulaCode
     * @return array|ProfileCode[]
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownFormulaToGetProfilesFor
     */
    public function getProfiles(FormulaCode $formulaCode): array
    {
        try {
            return array_map(
                function (string $profileValue) {
                    return ProfileCode::getIt($profileValue);
                },
                $this->getValue($formulaCode, self::PROFILES)
            );
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownFormulaToGetProfilesFor("Given formula code '{$formulaCode}' is unknown");
        }
    }

    /**
     * @param FormulaCode $formulaCode
     * @return array|ModifierCode[]
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownFormulaToGetModifiersFor
     */
    public function getModifiers(FormulaCode $formulaCode): array
    {
        try {
            return array_map(
                function (string $modifierValue) {
                    return ModifierCode::getIt($modifierValue);
                },
                $this->getValue($formulaCode, self::MODIFIERS)
            );
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownFormulaToGetModifiersFor("Given formula code '{$formulaCode}' is unknown");
        }
    }
}