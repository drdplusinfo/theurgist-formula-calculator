<?php
declare(strict_types = 1);

namespace DrdPlus\Tables\Theurgist\Spells;

use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use DrdPlus\Codes\Theurgist\FormCode;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ProfileCode;
use DrdPlus\Codes\Theurgist\SpellTraitCode;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Noise;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Attack;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\CastingRounds;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\NumberOfConditions;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DifficultyChange;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Grafts;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Invisibility;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\NumberOfWaypoints;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Power;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Quality;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Radius;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Resistance;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\NumberOfSituations;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\EpicenterShift;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Threshold;

class ModifiersTable extends AbstractFileTable
{
    use ToFlatArrayTrait;

    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/modifiers.csv';
    }

    public const REALM = 'realm';
    public const REALMS_AFFECTION = 'realms_affection';
    public const AFFECTION_TYPE = 'affection_type';
    public const CASTING_ROUNDS = 'casting_rounds';
    public const DIFFICULTY_CHANGE = 'difficulty_change';
    public const RADIUS = 'radius';
    public const EPICENTER_SHIFT = 'epicenter_shift';
    public const POWER = 'power';
    public const NOISE = 'noise';
    public const ATTACK = 'attack';
    public const GRAFTS = 'grafts';
    public const SPELL_SPEED = 'spell_speed';
    public const NUMBER_OF_WAYPOINTS = 'number_of_waypoints';
    public const INVISIBILITY = 'invisibility';
    public const QUALITY = 'quality';
    public const NUMBER_OF_CONDITIONS = 'number_of_conditions';
    public const RESISTANCE = 'resistance';
    public const NUMBER_OF_SITUATIONS = 'number_of_situations';
    public const THRESHOLD = 'threshold';
    public const FORMS = 'forms';
    public const SPELL_TRAITS = 'spell_traits';
    public const PROFILES = 'profiles';
    public const FORMULAS = 'formulas';
    public const PARENT_MODIFIERS = 'parent_modifiers';
    public const CHILD_MODIFIERS = 'child_modifiers';

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::REALM => self::POSITIVE_INTEGER,
            self::REALMS_AFFECTION => self::ARRAY,
            self::CASTING_ROUNDS => self::ARRAY,
            self::DIFFICULTY_CHANGE => self::POSITIVE_INTEGER,
            self::RADIUS => self::ARRAY,
            self::EPICENTER_SHIFT => self::ARRAY,
            self::POWER => self::ARRAY,
            self::NOISE => self::ARRAY,
            self::ATTACK => self::ARRAY,
            self::GRAFTS => self::ARRAY,
            self::SPELL_SPEED => self::ARRAY,
            self::NUMBER_OF_WAYPOINTS => self::ARRAY,
            self::INVISIBILITY => self::ARRAY,
            self::QUALITY => self::ARRAY,
            self::NUMBER_OF_CONDITIONS => self::ARRAY,
            self::RESISTANCE => self::ARRAY,
            self::NUMBER_OF_SITUATIONS => self::ARRAY,
            self::THRESHOLD => self::ARRAY,
            self::FORMS => self::ARRAY,
            self::SPELL_TRAITS => self::ARRAY,
            self::PROFILES => self::ARRAY,
            self::FORMULAS => self::ARRAY,
            self::PARENT_MODIFIERS => self::ARRAY,
            self::CHILD_MODIFIERS => self::ARRAY,
        ];
    }

    const MODIFIER = 'modifier';

    protected function getRowsHeader(): array
    {
        return [
            self::MODIFIER,
        ];
    }

    /**
     * @param ModifierCode $modifierCode
     * @return Realm
     */
    public function getRealm(ModifierCode $modifierCode): Realm
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Realm($this->getValue($modifierCode, self::REALM));
    }

    /**
     * @param ModifierCode $modifierCode
     * @return RealmsAffection|null
     */
    public function getRealmsAffection(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $affectionValues = $this->getValue($modifierCode, self::REALMS_AFFECTION);
        if (count($affectionValues) === 0) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new RealmsAffection($affectionValues);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return CastingRounds
     */
    public function getCastingRounds(ModifierCode $modifierCode): CastingRounds
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new CastingRounds($this->getValue($modifierCode, self::CASTING_ROUNDS));
    }

    /**
     * @param ModifierCode $modifierCode
     * @return DifficultyChange
     */
    public function getDifficultyChange(ModifierCode $modifierCode): DifficultyChange
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new DifficultyChange($this->getValue($modifierCode, self::DIFFICULTY_CHANGE));
    }

    /**
     * @param ModifierCode $modifierCode
     * @return Radius|null
     */
    public function getRadius(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $radiusValues = $this->getValue($modifierCode, self::RADIUS);
        if (!$radiusValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Radius($radiusValues);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return EpicenterShift|null
     */
    public function getEpicenterShift(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $shiftValues = $this->getValue($modifierCode, self::EPICENTER_SHIFT);
        if (!$shiftValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new EpicenterShift($shiftValues);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return Power|null
     */
    public function getPower(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $powerValues = $this->getValue($modifierCode, self::POWER);
        if (!$powerValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Power($powerValues);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return Noise|null
     */
    public function getNoise(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $noiseValues = $this->getValue($modifierCode, self::NOISE);
        if (!$noiseValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Noise($noiseValues);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return Attack|null
     */
    public function getAttack(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $attackValues = $this->getValue($modifierCode, self::ATTACK);
        if (!$attackValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Attack($attackValues);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return Grafts|null
     */
    public function getGrafts(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $graftsValues = $this->getValue($modifierCode, self::GRAFTS);
        if (!$graftsValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Grafts($graftsValues);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return SpellSpeed|null
     */
    public function getSpellSpeed(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $speedValues = $this->getValue($modifierCode, self::SPELL_SPEED);
        if (!$speedValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new SpellSpeed($speedValues);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return NumberOfWaypoints|null
     */
    public function getNumberOfWaypoints(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $numberOfWaypointsValues = $this->getValue($modifierCode, self::NUMBER_OF_WAYPOINTS);
        if (!$numberOfWaypointsValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new NumberOfWaypoints($numberOfWaypointsValues);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return Invisibility|null
     */
    public function getInvisibility(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $invisibilityValues = $this->getValue($modifierCode, self::INVISIBILITY);
        if (!$invisibilityValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Invisibility($invisibilityValues);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return Quality|null
     */
    public function getQuality(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $qualityValues = $this->getValue($modifierCode, self::QUALITY);
        if (!$qualityValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Quality($qualityValues);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return NumberOfConditions|null
     */
    public function getNumberOfConditions(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $conditionsValues = $this->getValue($modifierCode, self::NUMBER_OF_CONDITIONS);
        if (!$conditionsValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new NumberOfConditions($conditionsValues);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return Resistance|null
     */
    public function getResistance(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $resistanceValue = $this->getValue($modifierCode, self::RESISTANCE);
        if (!$resistanceValue) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Resistance($resistanceValue);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return NumberOfSituations|null
     */
    public function getNumberOfSituations(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $numberOfSituationsValue = $this->getValue($modifierCode, self::NUMBER_OF_SITUATIONS);
        if (!$numberOfSituationsValue) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new NumberOfSituations($numberOfSituationsValue);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return Threshold|null
     */
    public function getThreshold(ModifierCode $modifierCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $thresholdValues = $this->getValue($modifierCode, self::THRESHOLD);
        if (!$thresholdValues) {
            return null;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Threshold($thresholdValues);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return array|FormCode[]
     */
    public function getForms(ModifierCode $modifierCode): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return array_map(
            function (string $formValue) {
                return FormCode::getIt($formValue);
            },
            $this->getValue($modifierCode, self::FORMS)
        );
    }

    /**
     * @param ModifierCode $modifierCode
     * @return array|SpellTraitCode[]
     */
    public function getSpellTraitCodes(ModifierCode $modifierCode): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return array_map(
            function (string $spellTraitValue) {
                /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                return SpellTraitCode::getIt($spellTraitValue);
            },
            $this->getValue($modifierCode, self::SPELL_TRAITS)
        );
    }

    /**
     * @param ModifierCode $modifierCode
     * @return array|ProfileCode[]
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownModifierToGetProfilesFor
     */
    public function getProfiles(ModifierCode $modifierCode): array
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return array_map(
                function (string $profileValue) {
                    return ProfileCode::getIt($profileValue);
                },
                $this->getValue($modifierCode, self::PROFILES)
            );
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownModifierToGetProfilesFor("Given modifier code '{$modifierCode}' is unknown");
        }
    }

    /**
     * @param ModifierCode $modifierCode
     * @return array|FormulaCode[]
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownModifierToGetFormulasFor
     */
    public function getFormulaCodes(ModifierCode $modifierCode): array
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return array_map(
                function (string $formulaValue) {
                    return FormulaCode::getIt($formulaValue);
                },
                $this->getValue($modifierCode, self::FORMULAS)
            );
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownModifierToGetFormulasFor("Given modifier code '{$modifierCode}' is unknown");
        }
    }

    /**
     * @param ModifierCode $modifierCode
     * @return array|ModifierCode[]
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownModifierToGetParentModifiersFor
     */
    public function getParentModifierCodes(ModifierCode $modifierCode): array
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return array_map(
                function (string $modifierValue) {
                    return ModifierCode::getIt($modifierValue);
                },
                $this->getValue($modifierCode, self::PARENT_MODIFIERS)
            );
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownModifierToGetParentModifiersFor(
                "Given modifier code '{$modifierCode}' is unknown"
            );
        }
    }

    /**
     * @param ModifierCode $modifierCode
     * @return array|ModifierCode[]
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownModifierToGetChildModifiersFor
     */
    public function getChildModifiers(ModifierCode $modifierCode): array
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return array_map(
                function (string $modifierValue) {
                    return ModifierCode::getIt($modifierValue);
                },
                $this->getValue($modifierCode, self::CHILD_MODIFIERS)
            );
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownModifierToGetChildModifiersFor(
                "Given modifier code '{$modifierCode}' is unknown"
            );
        }
    }

}