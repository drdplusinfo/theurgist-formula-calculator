<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\FormulaMutableSpellParameterCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ModifierMutableSpellParameterCode;
use DrdPlus\Tables\Tables;
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
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;
use Granam\String\StringTools;
use Granam\Tools\ValueDescriber;

class Formula extends StrictObject
{
    use ToFlatArrayTrait;

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
     * by @param array|Modifier[] $modifiers
     * @param array|SpellTrait[] $formulaSpellTraits
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UselessValueForUnusedSpellParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownFormulaParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidValueForFormulaParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidModifier
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidSpellTrait
     * @see FormulaMutableSpellParameterCode value indexed its value change
     */
    public function __construct(
        FormulaCode $formulaCode,
        Tables $tables,
        array $formulaSpellParameterValues = [],
        array $modifiers = [],
        array $formulaSpellTraits = []
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
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UselessValueForUnusedSpellParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidValueForFormulaParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownFormulaParameter
     */
    private function sanitizeSpellParameterChanges(array $spellParameterValues): array
    {
        $sanitizedChanges = [];
        foreach (FormulaMutableSpellParameterCode::getPossibleValues() as $mutableSpellParameter) {
            if (!\array_key_exists($mutableSpellParameter, $spellParameterValues)) {
                $sanitizedChanges[$mutableSpellParameter] = 0;
                continue;
            }
            try {
                $sanitizedValue = ToInteger::toInteger($spellParameterValues[$mutableSpellParameter]);
            } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
                throw new Exceptions\InvalidValueForFormulaParameter(
                    'Expected integer, got ' . ValueDescriber::describe($spellParameterValues[$mutableSpellParameter])
                    . ' for ' . $mutableSpellParameter . ": '{$exception->getMessage()}'"
                );
            }
            /** like @see FormulasTable::getCastingRounds() */
            $getParameter = StringTools::assembleGetterForName($mutableSpellParameter);
            /** @var CastingParameter $baseParameter */
            $baseParameter = $this->tables->getFormulasTable()->$getParameter($this->getFormulaCode());
            if ($baseParameter === null) {
                throw new Exceptions\UselessValueForUnusedSpellParameter(
                    "Casting parameter {$mutableSpellParameter} is not used for formula {$this->formulaCode}"
                    . ', so given non-zero addition ' . ValueDescriber::describe($spellParameterValues[$mutableSpellParameter])
                    . ' is thrown away'
                );
            }
            $parameterChange = $sanitizedValue - $baseParameter->getDefaultValue();
            $sanitizedChanges[$mutableSpellParameter] = $parameterChange;

            unset($spellParameterValues[$mutableSpellParameter]);
        }
        if (\count($spellParameterValues) > 0) { // there are some remains
            throw new Exceptions\UnknownFormulaParameter(
                'Unexpected mutable spells parameter(s) [' . \implode(', ', array_keys($spellParameterValues)) . ']. Expected only '
                . \implode(', ', FormulaMutableSpellParameterCode::getPossibleValues())
            );
        }

        return $sanitizedChanges;
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
                    'Expected instance of ' . Modifier::class . ', got ' . ValueDescriber::describe($spellTrait)
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

        return $difficulty->createWithChange(
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

    public function getCurrentCastingRounds(): CastingRounds
    {
        $castingRoundsSum = 0;
        foreach ($this->modifiers as $modifier) {
            $castingRoundsSum += $modifier->getCastingRounds()->getValue();
        }
        $castingRoundsSum += $this->tables->getFormulasTable()->getCastingRounds($this->getFormulaCode())->getValue();

        return new CastingRounds([$castingRoundsSum, 0], $this->tables);
    }

    /**
     * Evocation time is not affected by any modifier or trait.
     *
     * @return Evocation
     */
    public function getCurrentEvocation(): Evocation
    {
        return $this->tables->getFormulasTable()->getEvocation($this->getFormulaCode());
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
        $baseRealmsAffection = $this->tables->getFormulasTable()->getRealmsAffection($this->getFormulaCode());
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
            if (!array_key_exists($modifierRealmsAffectionPeriod, $realmsAffectionsSum)) {
                $realmsAffectionsSum[$modifierRealmsAffectionPeriod] = 0;
            }
            $realmsAffectionsSum[$modifierRealmsAffectionPeriod] += $modifierRealmsAffection->getValue();
        }

        return $realmsAffectionsSum;
    }

    /**
     * Gives the highest required realm (by difficulty, by formula itself or by one of its modifiers)
     *
     * @return Realm
     */
    public function getRequiredRealm(): Realm
    {
        $realmsIncrement = $this->getCurrentDifficulty()->getCurrentRealmsIncrement();
        $realm = $this->tables->getFormulasTable()->getRealm($this->getFormulaCode());
        $requiredRealm = $realm->add($realmsIncrement);

        foreach ($this->modifiers as $modifier) {
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

        $radiusModifiersChange = $this->getParameterBonusFromModifiers(ModifierMutableSpellParameterCode::SPELL_RADIUS);
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
        $baseSpellRadius = $this->tables->getFormulasTable()->getSpellRadius($this->formulaCode);
        if ($baseSpellRadius === null) {
            return null;
        }

        return $baseSpellRadius->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableSpellParameterCode::SPELL_RADIUS]);
    }

    /**
     * Any formula (spell) can be shifted
     *
     * @return EpicenterShift|null
     */
    public function getCurrentEpicenterShift(): ?EpicenterShift
    {
        $epicenterShiftWithAddition = $this->getEpicenterShiftWithAddition();
        $epicenterShiftByModifiers = $this->getParameterBonusFromModifiers(ModifierMutableSpellParameterCode::EPICENTER_SHIFT);
        if ($epicenterShiftWithAddition === null) {
            if ($epicenterShiftByModifiers === false) {
                return null;
            }

            return new EpicenterShift(
                [$epicenterShiftByModifiers['bonus'], 0 /* no added difficulty*/],
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

        return new EpicenterShift([$distance->getBonus(), 0 /* no added difficulty */], $this->tables, $distance);
    }

    private function getEpicenterShiftWithAddition(): ?EpicenterShift
    {
        $baseEpicenterShift = $this->tables->getFormulasTable()->getEpicenterShift($this->formulaCode);
        if ($baseEpicenterShift === null) {
            return null;
        }

        return $baseEpicenterShift->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableSpellParameterCode::EPICENTER_SHIFT]);
    }

    /**
     * Any formula (spell) can get a power, even if was passive and not harming before
     *
     * @return SpellPower|null
     */
    public function getCurrentSpellPower(): ?SpellPower
    {
        $powerWithAddition = $this->getSpellPowerWithAddition();
        $powerBonus = $this->getParameterBonusFromModifiers(ModifierMutableSpellParameterCode::SPELL_POWER);
        if (!$powerWithAddition && $powerBonus === false) {
            return null;
        }

        return new SpellPower(
            [
                ($powerWithAddition
                    ? $powerWithAddition->getValue()
                    : 0)
                + (int)$powerBonus,
                0, // no addition
            ],
            Tables::getIt()
        );
    }

    private function getSpellPowerWithAddition(): ?SpellPower
    {
        $basePower = $this->tables->getFormulasTable()->getSpellPower($this->formulaCode);
        if ($basePower === null) {
            return null;
        }

        return $basePower->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableSpellParameterCode::SPELL_POWER]);
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
                + (int)$this->getParameterBonusFromModifiers(ModifierMutableSpellParameterCode::SPELL_ATTACK),
                0 // no addition
            ],
            Tables::getIt()
        );
    }

    private function getSpellAttackWithAddition(): ?SpellAttack
    {
        $baseSpellAttack = $this->tables->getFormulasTable()->getSpellAttack($this->formulaCode);
        if ($baseSpellAttack === null) {
            return null;
        }

        return $baseSpellAttack->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableSpellParameterCode::SPELL_ATTACK]);
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
            if ($parameterName === ModifierMutableSpellParameterCode::SPELL_POWER
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
        if ($parameterName === ModifierMutableSpellParameterCode::EPICENTER_SHIFT) {
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
        $spellSpeedBonus = $this->getParameterBonusFromModifiers(ModifierMutableSpellParameterCode::SPELL_SPEED);
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

    private function getSpellSpeedWithAddition(): ?SpellSpeed
    {
        $baseSpellSpeed = $this->tables->getFormulasTable()->getSpellSpeed($this->formulaCode);
        if ($baseSpellSpeed === null) {
            return null;
        }

        return $baseSpellSpeed->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableSpellParameterCode::SPELL_SPEED]);
    }

    public function getCurrentDetailLevel(): ?DetailLevel
    {
        return $this->getDetailLevelWithAddition();
    }

    private function getDetailLevelWithAddition(): ?DetailLevel
    {
        $baseDetailLevel = $this->tables->getFormulasTable()->getDetailLevel($this->formulaCode);
        if ($baseDetailLevel === null) {
            return null;
        }

        return $baseDetailLevel->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableSpellParameterCode::DETAIL_LEVEL]);
    }

    public function getCurrentSpellBrightness(): ?SpellBrightness
    {
        return $this->getSpellBrightnessWithAddition();
    }

    private function getSpellBrightnessWithAddition(): ?SpellBrightness
    {
        $baseSpellBrightness = $this->tables->getFormulasTable()->getSpellBrightness($this->formulaCode);
        if ($baseSpellBrightness === null) {
            return null;
        }

        return $baseSpellBrightness->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableSpellParameterCode::SPELL_BRIGHTNESS]);
    }

    public function getCurrentSpellDuration(): SpellDuration
    {
        return $this->getDurationWithAddition();
    }

    private function getDurationWithAddition(): SpellDuration
    {
        $baseDuration = $this->tables->getFormulasTable()->getSpellDuration($this->formulaCode);

        return $baseDuration->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableSpellParameterCode::SPELL_DURATION]);
    }

    public function getCurrentSizeChange(): ?SizeChange
    {
        return $this->getSizeChangeWithAddition();
    }

    private function getSizeChangeWithAddition(): ?SizeChange
    {
        $baseSizeChange = $this->tables->getFormulasTable()->getSizeChange($this->formulaCode);
        if ($baseSizeChange === null) {
            return null;
        }

        return $baseSizeChange->getWithAddition($this->formulaSpellParameterChanges[FormulaMutableSpellParameterCode::SIZE_CHANGE]);
    }

    public function __toString()
    {
        return $this->getFormulaCode()->getValue();
    }

}