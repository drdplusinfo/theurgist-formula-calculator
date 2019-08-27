<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\FormulaMutableParameterCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ModifierMutableParameterCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Exceptions\InvalidValueForMutableParameter;
use DrdPlus\Tables\Theurgist\Exceptions\UnknownParameter;
use DrdPlus\Tables\Theurgist\Partials\SanitizeMutableParameterChangesTrait;
use DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidValueForFormulaParameter;
use DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownFormulaParameter;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellAttack;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellBrightness;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\CastingRounds;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DetailLevel;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellDuration;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\EpicenterShift;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Evocation;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Difficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellPower;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellRadius;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SizeChange;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;
use Granam\Strict\Object\StrictObject;
use Granam\String\StringTools;
use Granam\Tools\ValueDescriber;

class Formula extends StrictObject
{
    use ToFlatArrayTrait;
    use SanitizeMutableParameterChangesTrait;

    private const NO_ADDITION_BY_DIFFICULTY = 0;

    /** @var FormulaCode */
    private $formulaCode;
    /** @var Tables */
    private $tables;
    /** @var int[] */
    private $formulaSpellParameterChanges;
    /** @var Modifier[] */
    private $modifiers;
    /** @var SpellTrait[] */
    private $formulaSpellTraits;

    /**
     * @param FormulaCode $formulaCode
     * @param Tables $tables
     * @param array $formulaSpellParameterValues Current values of spell parameters (changes will be calculated from them)
     * @param array|Modifier[] $modifiers
     * @param array|SpellTrait[] $formulaSpellTraits
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownFormulaParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidValueForFormulaParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidModifier
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidSpellTrait
     * @see FormulaMutableParameterCode value indexed its value change
     */
    public function __construct(
        FormulaCode $formulaCode,
        Tables $tables,
        array $formulaSpellParameterValues,
        array $modifiers,
        array $formulaSpellTraits
    )
    {
        $this->formulaCode = $formulaCode;
        $this->tables = $tables;
        // gets spell parameter changes as delta of current values and default values
        $this->formulaSpellParameterChanges = $this->sanitizeSpellParameterChanges($formulaSpellParameterValues);
        $this->modifiers = $this->getCheckedModifiers($this->toFlatArray($modifiers));
        $this->formulaSpellTraits = $this->getCheckedSpellTraits($this->toFlatArray($formulaSpellTraits));
    }

    /**
     * @param array $spellParameterValues
     * @return array
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidValueForFormulaParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownFormulaParameter
     */
    private function sanitizeSpellParameterChanges(array $spellParameterValues): array
    {
        try {
            return $this->sanitizeMutableParameterChanges(
                $spellParameterValues,
                FormulaMutableParameterCode::getPossibleValues(),
                $this->getFormulaCode(),
                $this->tables->getFormulasTable()
            );
        } catch (InvalidValueForMutableParameter $invalidValueForMutableParameter) {
            throw new InvalidValueForFormulaParameter(
                $invalidValueForMutableParameter->getMessage(),
                $invalidValueForMutableParameter->getCode(),
                $invalidValueForMutableParameter
            );
        } catch (UnknownParameter $unknownParameter) {
            throw new UnknownFormulaParameter(
                $unknownParameter->getMessage(),
                $unknownParameter->getCode(),
                $unknownParameter
            );
        }
    }

    /**
     * @param array|Modifier[] $modifiers
     * @return array|Modifier[]
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidModifier
     */
    private function getCheckedModifiers(array $modifiers): array
    {
        foreach ($modifiers as $modifier) {
            if (!is_a($modifier, Modifier::class)) {
                throw new Exceptions\InvalidModifier(
                    'Expected instance of ' . Modifier::class . ', got ' . ValueDescriber::describe($modifier)
                );
            }
        }

        return $modifiers;
    }

    /**
     * @param array|SpellTrait[] $spellTraits
     * @return array|SpellTrait[]
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidSpellTrait
     */
    private function getCheckedSpellTraits(array $spellTraits): array
    {
        foreach ($spellTraits as $spellTrait) {
            if (!is_a($spellTrait, SpellTrait::class)) {
                throw new Exceptions\InvalidSpellTrait(
                    'Expected instance of ' . SpellTrait::class . ', got ' . ValueDescriber::describe($spellTrait)
                );
            }
        }

        return $spellTraits;
    }

    /**
     * All modifiers in a flat array (with removed tree structure)
     *
     * @return array|Modifier[]
     */
    public function getModifiers(): array
    {
        return $this->modifiers;
    }

    public function getCurrentDifficulty(): Difficulty
    {
        $parametersDifficultyChangeSum = 0;
        foreach ($this->getFormulaParameters() as $formulaParameter) {
            $parametersDifficultyChangeSum += $formulaParameter->getAdditionByDifficulty()->getCurrentDifficultyIncrement();
        }
        $modifiersDifficultyChangeSum = 0;
        foreach ($this->modifiers as $modifier) {
            $modifiersDifficultyChangeSum += $modifier->getDifficultyChange()->getValue();
        }
        $spellTraitsDifficultyChangeSum = 0;
        foreach ($this->formulaSpellTraits as $spellTrait) {
            $spellTraitsDifficultyChangeSum += $spellTrait->getDifficultyChange()->getValue();
        }
        $difficulty = $this->tables->getFormulasTable()->getDifficulty($this->getFormulaCode());

        return $difficulty->getWithDifficultyChange(
            $parametersDifficultyChangeSum
            + $modifiersDifficultyChangeSum
            + $spellTraitsDifficultyChangeSum
        );
    }

    /**
     * @return array|CastingParameter[]
     */
    private function getFormulaParameters(): array
    {
        return array_filter(
            [
                $this->getSpellAttackWithAddition(),
                $this->getSpellBrightnessWithAddition(),
                $this->getDetailLevelWithAddition(),
                $this->getDurationWithAddition(),
                $this->getEpicenterShiftWithAddition(),
                $this->getSpellPowerWithAddition(),
                $this->getRadiusWithAddition(),
                $this->getSizeChangeWithAddition(),
                $this->getSpellSpeedWithAddition(),
            ],
            function (CastingParameter $formulaParameter = null) {
                return $formulaParameter !== null;
            }
        );
    }

    public function getBaseCastingRounds(): CastingRounds
    {
        return $this->tables->getFormulasTable()->getCastingRounds($this->getFormulaCode());
    }

    public function getCurrentCastingRounds(): CastingRounds
    {
        $castingRoundsSum = 0;
        foreach ($this->modifiers as $modifier) {
            $castingRoundsSum += $modifier->getCastingRounds()->getValue();
        }
        $castingRoundsSum += $this->getBaseCastingRounds()->getValue();

        return new CastingRounds([$castingRoundsSum, 0], $this->tables);
    }

    public function getBaseEvocation(): Evocation
    {
        return $this->tables->getFormulasTable()->getEvocation($this->getFormulaCode());
    }

    /**
     * Evocation time is not affected by any modifier or trait.
     *
     * @return Evocation
     */
    public function getCurrentEvocation(): Evocation
    {
        return $this->getBaseEvocation();
    }

    public function getBaseRealmAffection(): RealmsAffection
    {
        return $this->tables->getFormulasTable()->getRealmsAffection($this->getFormulaCode());
    }

    /**
     * Daily, monthly and lifetime affections of realms
     *
     * @return array|RealmsAffection[]
     */
    public function getCurrentRealmsAffections(): array
    {
        $realmsAffections = [];
        foreach ($this->getRealmsAffectionsSum() as $periodName => $periodSum) {
            $realmsAffections[$periodName] = new RealmsAffection([$periodSum, $periodName]);
        }

        return $realmsAffections;
    }

    /**
     * @return array|int[] by affection period indexed summary of that period realms-affection
     */
    private function getRealmsAffectionsSum(): array
    {
        $baseRealmsAffection = $this->getBaseRealmAffection();
        $realmsAffectionsSum = [
            // like daily => -2
            $baseRealmsAffection->getAffectionPeriodCode()->getValue() => $baseRealmsAffection->getValue(),
        ];
        foreach ($this->modifiers as $modifier) {
            $modifierRealmsAffection = $modifier->getRealmsAffection();
            if ($modifierRealmsAffection === null) {
                continue;
            }
            $modifierRealmsAffectionPeriod = $modifierRealmsAffection->getAffectionPeriodCode()->getValue();
            $realmsAffectionsSum[$modifierRealmsAffectionPeriod] = ($realmsAffectionsSum[$modifierRealmsAffectionPeriod] ?? 0) + $modifierRealmsAffection->getValue();
        }

        return $realmsAffectionsSum;
    }

    public function getBaseRealm(): Realm
    {
        return $this->tables->getFormulasTable()->getRealm($this->getFormulaCode());
    }

    /**
     * Gives the highest required realm (by difficulty, by formula itself or by one of its modifiers)
     *
     * @return Realm
     */
    public function getRequiredRealm(): Realm
    {
        $baseRealm = $this->getBaseRealm();

        $realmsIncrementBecauseOfDifficulty = $this->getCurrentDifficulty()->getCurrentRealmsIncrement();
        $requiredRealm = $baseRealm->add($realmsIncrementBecauseOfDifficulty);

        foreach ($this->getModifiers() as $modifier) {
            $byModifierRequiredRealm = $modifier->getRequiredRealm();
            if ($requiredRealm->getValue() < $byModifierRequiredRealm->getValue()) {
                // some modifier requires even higher realm, so we are forced to increase it
                $requiredRealm = $byModifierRequiredRealm;
            }
        }

        return $requiredRealm;
    }

    public function getFormulaCode(): FormulaCode
    {
        return $this->formulaCode;
    }

    public function getBaseSpellRadius(): ?SpellRadius
    {
        return $this->tables->getFormulasTable()->getSpellRadius($this->getFormulaCode());
    }

    /**
     * Final radius including direct formula change and all its active traits and modifiers.
     *
     * @return SpellRadius|null
     */
    public function getCurrentSpellRadius(): ?SpellRadius
    {
        $radiusWithAddition = $this->getRadiusWithAddition();
        if (!$radiusWithAddition) {
            return null;
        }

        $radiusModifiersChange = $this->getParameterBonusFromModifiers(ModifierMutableParameterCode::SPELL_RADIUS);
        if (!$radiusModifiersChange) {
            return new SpellRadius([$radiusWithAddition->getValue(), 0], $this->tables);
        }

        return new SpellRadius([$radiusWithAddition->getValue() + $radiusModifiersChange, 0], $this->tables);
    }

    /**
     * Formula radius extended by direct formula change
     *
     * @return SpellRadius|null
     */
    private function getRadiusWithAddition(): ?SpellRadius
    {
        $baseSpellRadius = $this->getBaseSpellRadius();
        if ($baseSpellRadius === null) {
            return null;
        }

        return $baseSpellRadius->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableParameterCode::SPELL_RADIUS]);
    }

    /**
     * Any formula (spell) can be shifted
     *
     * @return EpicenterShift|null
     */
    public function getCurrentEpicenterShift(): ?EpicenterShift
    {
        $epicenterShiftWithAddition = $this->getEpicenterShiftWithAddition();
        $epicenterShiftByModifiers = $this->getParameterBonusFromModifiers(ModifierMutableParameterCode::EPICENTER_SHIFT);
        if ($epicenterShiftWithAddition === null) {
            if ($epicenterShiftByModifiers === false) {
                return null;
            }

            // Transposition modifier can shift epicenter even if formula itself can not (if formula supports Transposition of course)
            return new EpicenterShift(
                [$epicenterShiftByModifiers['bonus'], self::NO_ADDITION_BY_DIFFICULTY],
                $this->tables,
                new Distance($epicenterShiftByModifiers['meters'], DistanceUnitCode::METER, $this->tables->getDistanceTable())
            );
        }
        if ($epicenterShiftByModifiers === false) {
            return $epicenterShiftWithAddition;
        }
        $meters = $epicenterShiftWithAddition->getDistance()->getMeters();
        $meters += $epicenterShiftByModifiers['meters'];

        $distance = new Distance($meters, DistanceUnitCode::METER, $this->tables->getDistanceTable());

        return new EpicenterShift([$distance->getBonus(), self::NO_ADDITION_BY_DIFFICULTY], $this->tables, $distance);
    }

    public function getBaseEpicenterShift(): ?EpicenterShift
    {
        return $this->tables->getFormulasTable()->getEpicenterShift($this->getFormulaCode());
    }

    private function getEpicenterShiftWithAddition(): ?EpicenterShift
    {
        $baseEpicenterShift = $this->getBaseEpicenterShift();
        if ($baseEpicenterShift === null) {
            return null;
        }

        return $baseEpicenterShift->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableParameterCode::EPICENTER_SHIFT]);
    }

    /**
     * Any formula (spell) can get a power, even if was passive and not harming before
     *
     * @return SpellPower|null
     */
    public function getCurrentSpellPower(): ?SpellPower
    {
        $spellPowerWithAddition = $this->getSpellPowerWithAddition();
        $spellPowerBonus = $this->getParameterBonusFromModifiers(ModifierMutableParameterCode::SPELL_POWER);
        if (!$spellPowerWithAddition && $spellPowerBonus === false) {
            return null;
        }

        return new SpellPower(
            [
                ($spellPowerWithAddition
                    ? $spellPowerWithAddition->getValue()
                    : 0)
                + (int)$spellPowerBonus,
                self::NO_ADDITION_BY_DIFFICULTY,
            ],
            Tables::getIt()
        );
    }

    public function getBaseSpellPower(): ?SpellPower
    {
        return $this->tables->getFormulasTable()->getSpellPower($this->getFormulaCode());
    }

    private function getSpellPowerWithAddition(): ?SpellPower
    {
        $baseSpellPower = $this->getBaseSpellPower();
        if ($baseSpellPower === null) {
            return null;
        }

        return $baseSpellPower->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableParameterCode::SPELL_POWER]);
    }

    /**
     * Attack can be only increased, not added.
     *
     * @return SpellAttack|null
     */
    public function getCurrentSpellAttack(): ?SpellAttack
    {
        $spellAttackWithAddition = $this->getSpellAttackWithAddition();
        if (!$spellAttackWithAddition) {
            return null;
        }

        return new SpellAttack(
            [
                $spellAttackWithAddition->getValue()
                + (int)$this->getParameterBonusFromModifiers(ModifierMutableParameterCode::SPELL_ATTACK),
                self::NO_ADDITION_BY_DIFFICULTY,
            ],
            Tables::getIt()
        );
    }

    public function getBaseSpellAttack(): ?SpellAttack
    {
        return $this->tables->getFormulasTable()->getSpellAttack($this->getFormulaCode());
    }

    private function getSpellAttackWithAddition(): ?SpellAttack
    {
        $baseSpellAttack = $this->getBaseSpellAttack();
        if ($baseSpellAttack === null) {
            return null;
        }

        return $baseSpellAttack->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableParameterCode::SPELL_ATTACK]);
    }

    /**
     * @param string $parameterName
     * @return bool|int|array|int[]
     */
    private function getParameterBonusFromModifiers(string $parameterName)
    {
        $bonusParts = [];
        foreach ($this->modifiers as $modifier) {
            if ($modifier->getModifierCode()->getValue() === ModifierCode::GATE) {
                continue; // gate does not give bonus to a parameter, it is standalone being with its own parameters
            }
            if ($parameterName === ModifierMutableParameterCode::SPELL_POWER
                && $modifier->getModifierCode()->getValue() === ModifierCode::THUNDER
            ) {
                continue; // thunder power means a noise, does not affects formula power
            }
            $getParameterWithAddition = StringTools::assembleGetterForName($parameterName . 'WithAddition');
            /** like @see Modifier::getSpellAttackWithAddition() */
            $parameter = $modifier->$getParameterWithAddition();
            if ($parameter === null) {
                continue;
            }
            /** @var CastingParameter $parameter */
            $bonusParts[] = $parameter->getValue();
        }
        if (\count($bonusParts) === 0) {
            return false;
        }

        // transpositions are chained in sequence and their values (distances) have to be summed, not bonuses
        if ($parameterName === ModifierMutableParameterCode::EPICENTER_SHIFT) {
            $meters = 0;
            foreach ($bonusParts as $bonusPart) {
                $meters += (new DistanceBonus($bonusPart, $this->tables->getDistanceTable()))->getDistance()->getMeters();
            }

            return [
                'bonus' => (new Distance($meters, DistanceUnitCode::METER, $this->tables->getDistanceTable()))->getBonus()->getValue(),
                'meters' => $meters,
            ];
        }

        return (int)\array_sum($bonusParts);
    }

    /**
     * Any formula (spell) can get a speed, even if was static before
     *
     * @return SpellSpeed|null
     */
    public function getCurrentSpellSpeed(): ?SpellSpeed
    {
        $spellSpeedWithAddition = $this->getSpellSpeedWithAddition();
        $spellSpeedBonus = $this->getParameterBonusFromModifiers(ModifierMutableParameterCode::SPELL_SPEED);
        if (!$spellSpeedWithAddition && $spellSpeedBonus === false) {
            return null;
        }

        return new SpellSpeed(
            [
                ($spellSpeedWithAddition
                    ? $spellSpeedWithAddition->getValue()
                    : 0)
                + (int)$spellSpeedBonus,
                0,
            ],
            $this->tables
        );
    }

    public function getBaseSpellSpeed(): ?SpellSpeed
    {
        return $this->tables->getFormulasTable()->getSpellSpeed($this->getFormulaCode());
    }

    private function getSpellSpeedWithAddition(): ?SpellSpeed
    {
        $baseSpellSpeed = $this->getBaseSpellSpeed();
        if ($baseSpellSpeed === null) {
            return null;
        }

        return $baseSpellSpeed->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableParameterCode::SPELL_SPEED]);
    }

    public function getCurrentDetailLevel(): ?DetailLevel
    {
        return $this->getDetailLevelWithAddition();
    }

    public function getBaseDetailLevel(): ?DetailLevel
    {
        return $this->tables->getFormulasTable()->getDetailLevel($this->getFormulaCode());
    }

    private function getDetailLevelWithAddition(): ?DetailLevel
    {
        $baseDetailLevel = $this->getBaseDetailLevel();
        if ($baseDetailLevel === null) {
            return null;
        }

        return $baseDetailLevel->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableParameterCode::DETAIL_LEVEL]);
    }

    public function getCurrentSpellBrightness(): ?SpellBrightness
    {
        return $this->getSpellBrightnessWithAddition();
    }

    public function getBaseSpellBrightness(): ?SpellBrightness
    {
        return $this->tables->getFormulasTable()->getSpellBrightness($this->getFormulaCode());
    }

    private function getSpellBrightnessWithAddition(): ?SpellBrightness
    {
        $baseSpellBrightness = $this->getBaseSpellBrightness();
        if ($baseSpellBrightness === null) {
            return null;
        }

        return $baseSpellBrightness->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableParameterCode::SPELL_BRIGHTNESS]);
    }

    public function getCurrentSpellDuration(): SpellDuration
    {
        return $this->getDurationWithAddition();
    }

    public function getBaseSpellDuration()
    {
        return $this->tables->getFormulasTable()->getSpellDuration($this->getFormulaCode());
    }

    private function getDurationWithAddition(): SpellDuration
    {
        $baseSpellDuration = $this->getBaseSpellDuration();

        return $baseSpellDuration->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableParameterCode::SPELL_DURATION]);
    }

    public function getCurrentSizeChange(): ?SizeChange
    {
        return $this->getSizeChangeWithAddition();
    }

    public function getBaseSizeChange(): ?SizeChange
    {
        return $this->tables->getFormulasTable()->getSizeChange($this->getFormulaCode());
    }

    private function getSizeChangeWithAddition(): ?SizeChange
    {
        $baseSizeChange = $this->getBaseSizeChange();
        if ($baseSizeChange === null) {
            return null;
        }

        return $baseSizeChange->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableParameterCode::SIZE_CHANGE]);
    }

    public function __toString()
    {
        return $this->getFormulaCode()->getValue();
    }

}