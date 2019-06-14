<?php declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells;

use DrdPlus\Codes\Theurgist\ModifierMutableParameterCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use DrdPlus\Codes\Theurgist\FormCode;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ProfileCode;
use DrdPlus\Codes\Theurgist\SpellTraitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Noise;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellAttack;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\CastingRounds;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\NumberOfConditions;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DifficultyChange;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Grafts;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Invisibility;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\NumberOfWaypoints;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellPower;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Quality;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellRadius;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Resistance;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\NumberOfSituations;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\EpicenterShift;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Threshold;

/**
 * @link https://theurg.drdplus.info/#tabulka_modifikatoru
 */
class ModifiersTable extends AbstractFileTable
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

    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/modifiers.csv';
    }

    public const REALM = FormulasTable::REALM;
    public const REALMS_AFFECTION = FormulasTable::REALMS_AFFECTION;
    public const CASTING_ROUNDS = 'casting_rounds';
    public const DIFFICULTY_CHANGE = 'difficulty_change';
    public const RADIUS = FormulasTable::SPELL_RADIUS;
    public const EPICENTER_SHIFT = FormulasTable::EPICENTER_SHIFT;
    public const SPELL_POWER = FormulasTable::SPELL_POWER;
    public const NOISE = ModifierMutableParameterCode::NOISE;
    public const SPELL_ATTACK = FormulasTable::SPELL_ATTACK;
    public const GRAFTS = ModifierMutableParameterCode::GRAFTS;
    public const SPELL_SPEED = FormulasTable::SPELL_SPEED;
    public const NUMBER_OF_WAYPOINTS = ModifierMutableParameterCode::NUMBER_OF_WAYPOINTS;
    public const INVISIBILITY = ModifierMutableParameterCode::INVISIBILITY;
    public const QUALITY = ModifierMutableParameterCode::QUALITY;
    public const NUMBER_OF_CONDITIONS = ModifierMutableParameterCode::NUMBER_OF_CONDITIONS;
    public const RESISTANCE = ModifierMutableParameterCode::RESISTANCE;
    public const NUMBER_OF_SITUATIONS = ModifierMutableParameterCode::NUMBER_OF_SITUATIONS;
    public const THRESHOLD = ModifierMutableParameterCode::THRESHOLD;
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
            self::SPELL_POWER => self::ARRAY,
            self::NOISE => self::ARRAY,
            self::SPELL_ATTACK => self::ARRAY,
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

    public function getRealm(ModifierCode $modifierCode): Realm
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Realm($this->getValue($modifierCode, self::REALM));
    }

    public function getRealmsAffection(ModifierCode $modifierCode): ?RealmsAffection
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $affectionValues = $this->getValue($modifierCode, self::REALMS_AFFECTION);
        if (count($affectionValues) === 0) {
            return null;
        }
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new RealmsAffection($affectionValues);
    }

    public function getCastingRounds(ModifierCode $modifierCode): CastingRounds
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new CastingRounds($this->getValue($modifierCode, self::CASTING_ROUNDS), $this->tables);
    }

    public function getDifficultyChange(ModifierCode $modifierCode): DifficultyChange
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new DifficultyChange($this->getValue($modifierCode, self::DIFFICULTY_CHANGE));
    }

    public function getSpellRadius(ModifierCode $modifierCode): ?SpellRadius
    {
        $radiusValues = $this->getValue($modifierCode, self::RADIUS);
        if (!$radiusValues) {
            return null;
        }
        return new SpellRadius($radiusValues, $this->tables);
    }

    public function getEpicenterShift(ModifierCode $modifierCode): ?EpicenterShift
    {
        $shiftValues = $this->getValue($modifierCode, self::EPICENTER_SHIFT);
        if (!$shiftValues) {
            return null;
        }
        return new EpicenterShift($shiftValues, $this->tables);
    }

    public function getSpellPower(ModifierCode $modifierCode): ?SpellPower
    {
        $powerValues = $this->getValue($modifierCode, self::SPELL_POWER);
        if (!$powerValues) {
            return null;
        }
        return new SpellPower($powerValues, $this->tables);
    }

    public function getNoise(ModifierCode $modifierCode): ?Noise
    {
        $noiseValues = $this->getValue($modifierCode, self::NOISE);
        if (!$noiseValues) {
            return null;
        }
        return new Noise($noiseValues, $this->tables);
    }

    public function getSpellAttack(ModifierCode $modifierCode): ?SpellAttack
    {
        $attackValues = $this->getValue($modifierCode, self::SPELL_ATTACK);
        if (!$attackValues) {
            return null;
        }
        return new SpellAttack($attackValues, $this->tables);
    }

    public function getGrafts(ModifierCode $modifierCode): ?Grafts
    {
        $graftsValues = $this->getValue($modifierCode, self::GRAFTS);
        if (!$graftsValues) {
            return null;
        }
        return new Grafts($graftsValues, $this->tables);
    }

    public function getSpellSpeed(ModifierCode $modifierCode): ?SpellSpeed
    {
        $speedValues = $this->getValue($modifierCode, self::SPELL_SPEED);
        if (!$speedValues) {
            return null;
        }
        return new SpellSpeed($speedValues, $this->tables);
    }

    public function getNumberOfWaypoints(ModifierCode $modifierCode): ?NumberOfWaypoints
    {
        $numberOfWaypointsValues = $this->getValue($modifierCode, self::NUMBER_OF_WAYPOINTS);
        if (!$numberOfWaypointsValues) {
            return null;
        }
        return new NumberOfWaypoints($numberOfWaypointsValues, $this->tables);
    }

    public function getInvisibility(ModifierCode $modifierCode): ?Invisibility
    {
        $invisibilityValues = $this->getValue($modifierCode, self::INVISIBILITY);
        if (!$invisibilityValues) {
            return null;
        }
        return new Invisibility($invisibilityValues, $this->tables);
    }

    public function getQuality(ModifierCode $modifierCode): ?Quality
    {
        $qualityValues = $this->getValue($modifierCode, self::QUALITY);
        if (!$qualityValues) {
            return null;
        }
        return new Quality($qualityValues, $this->tables);
    }

    public function getNumberOfConditions(ModifierCode $modifierCode): ?NumberOfConditions
    {
        $conditionsValues = $this->getValue($modifierCode, self::NUMBER_OF_CONDITIONS);
        if (!$conditionsValues) {
            return null;
        }
        return new NumberOfConditions($conditionsValues, $this->tables);
    }

    public function getResistance(ModifierCode $modifierCode): ?Resistance
    {
        $resistanceValue = $this->getValue($modifierCode, self::RESISTANCE);
        if (!$resistanceValue) {
            return null;
        }
        return new Resistance($resistanceValue, $this->tables);
    }

    public function getNumberOfSituations(ModifierCode $modifierCode): ?NumberOfSituations
    {
        $numberOfSituationsValue = $this->getValue($modifierCode, self::NUMBER_OF_SITUATIONS);
        if (!$numberOfSituationsValue) {
            return null;
        }
        return new NumberOfSituations($numberOfSituationsValue, $this->tables);
    }

    public function getThreshold(ModifierCode $modifierCode): ?Threshold
    {
        $thresholdValues = $this->getValue($modifierCode, self::THRESHOLD);
        if (!$thresholdValues) {
            return null;
        }
        return new Threshold($thresholdValues, $this->tables);
    }

    /**
     * @param ModifierCode $modifierCode
     * @return array|FormCode[]
     */
    public function getFormCodes(ModifierCode $modifierCode): array
    {
        return array_map(
            function (string $formValue) {
                return FormCode::getIt($formValue);
            },
            $this->getValue($modifierCode, self::FORMS)
        );
    }

    /**
     * @param ModifierCode $modifierCode
     * @return array|SpellTrait[]
     */
    public function getSpellTraits(ModifierCode $modifierCode)
    {
        return array_map(
            function (SpellTraitCode $spellTraitCode) {
                return new SpellTrait($spellTraitCode, $this->tables);
            },
            $this->getSpellTraitCodes($modifierCode)
        );
    }

    /**
     * @param ModifierCode $modifierCode
     * @return array|SpellTraitCode[]
     */
    public function getSpellTraitCodes(ModifierCode $modifierCode): array
    {
        return array_map(
            function (string $spellTraitValue) {
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
    public function getProfileCodes(ModifierCode $modifierCode): array
    {
        try {
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
    public function getChildModifierCodes(ModifierCode $modifierCode): array
    {
        try {
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