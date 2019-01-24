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
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\CastingRounds;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Attack;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Brightness;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Evocation;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DetailLevel;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\FormulaDifficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Duration;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\EpicenterShift;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Power;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Radius;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SizeChange;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;

class FormulasTable extends AbstractFileTable
{
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/formulas.csv';
    }

    public const REALM = 'realm';
    public const REALMS_AFFECTION = 'realms_affection';
    public const EVOCATION = 'evocation';
    public const FORMULA_DIFFICULTY = 'formula_difficulty';
    public const RADIUS = 'radius';
    public const DURATION = 'duration';
    public const POWER = 'power';
    public const ATTACK = 'attack';
    public const SIZE_CHANGE = 'size_change';
    public const DETAIL_LEVEL = 'detail_level';
    public const BRIGHTNESS = 'brightness';
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
            self::FORMULA_DIFFICULTY => self::ARRAY,
            self::RADIUS => self::ARRAY,
            self::DURATION => self::ARRAY,
            self::POWER => self::ARRAY,
            self::ATTACK => self::ARRAY,
            self::SIZE_CHANGE => self::ARRAY,
            self::DETAIL_LEVEL => self::ARRAY,
            self::BRIGHTNESS => self::ARRAY,
            self::SPELL_SPEED => self::ARRAY,
            self::EPICENTER_SHIFT => self::ARRAY,
            self::FORMS => self::ARRAY,
            self::SPELL_TRAITS => self::ARRAY,
            self::PROFILES => self::ARRAY,
            self::MODIFIERS => self::ARRAY,
        ];
    }

    public const FORMULA = 'formula';

    protected function getRowsHeader(): array
    {
        return [
            self::FORMULA,
        ];
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Realm
     */
    public function getRealm(FormulaCode $formulaCode): Realm
    {
        return new Realm($this->getValue($formulaCode, self::REALM));
    }

    /**
     * @param FormulaCode $formulaCode
     * @return RealmsAffection
     */
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
        return new Evocation($this->getValue($formulaCode, self::EVOCATION));
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
        return new CastingRounds([1]);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return FormulaDifficulty
     */
    public function getFormulaDifficulty(FormulaCode $formulaCode): FormulaDifficulty
    {
        return new FormulaDifficulty($this->getValue($formulaCode, self::FORMULA_DIFFICULTY));
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Radius|null
     */
    public function getRadius(FormulaCode $formulaCode): ?Radius
    {
        $radiusValues = $this->getValue($formulaCode, self::RADIUS);
        if (!$radiusValues) {
            return null;
        }

        return new Radius($radiusValues);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Duration
     */
    public function getDuration(FormulaCode $formulaCode): Duration
    {
        return new Duration($this->getValue($formulaCode, self::DURATION));
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Power|null
     */
    public function getPower(FormulaCode $formulaCode): ?Power
    {
        $powerValues = $this->getValue($formulaCode, self::POWER);
        if (!$powerValues) {
            return null;
        }

        return new Power($powerValues);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Attack|null
     */
    public function getAttack(FormulaCode $formulaCode): ?Attack
    {
        $attackValues = $this->getValue($formulaCode, self::ATTACK);
        if (!$attackValues) {
            return null;
        }

        return new Attack($attackValues);
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

        return new SizeChange($sizeChangeValues);
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

        return new DetailLevel($detailLevelValues);
    }

    /**
     * @param FormulaCode $formulaCode
     * @return Brightness|null
     */
    public function getBrightness(FormulaCode $formulaCode): ?Brightness
    {
        $brightnessValues = $this->getValue($formulaCode, self::BRIGHTNESS);
        if (!$brightnessValues) {
            return null;
        }

        return new Brightness($brightnessValues);
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

        return new SpellSpeed($speedValues);
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

        return new EpicenterShift($epicenterShift);
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
     * @return array|SpellTraitCode[]
     */
    public function getSpellTraitCodes(FormulaCode $formulaCode): array
    {
        return array_map(
            function (string $spellTraitValue) {
                return SpellTraitCode::getIt($spellTraitValue);
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
    public function getModifierCodes(FormulaCode $formulaCode): array
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