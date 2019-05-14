<?php
declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\CalculatorSkeleton\CurrentValues;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\FormulaMutableSpellParameterCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\SpellTraitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\Formula;
use DrdPlus\Tables\Theurgist\Spells\Modifier;
use DrdPlus\Tables\Theurgist\Spells\SpellTrait;
use Granam\Integer\Tools\ToInteger;
use Granam\Number\NumberInterface;
use Granam\Strict\Object\StrictObject;

class CurrentFormulaValues extends StrictObject
{
    public const FORMULA = 'formula';
    public const MODIFIERS = 'modifiers';
    public const PREVIOUS_FORMULA = 'previous_formula';
    public const FORMULA_PARAMETERS = 'formula_parameters';
    public const FORMULA_SPELL_TRAITS = 'formula_spell_traits';
    public const MODIFIER_SPELL_TRAITS = 'modifier_spell_traits';
    public const MODIFIER_SPELL_TRAIT_TRAPS = 'modifier_spell_trait_traps';
    public const MODIFIER_PARAMETERS = 'modifier_parameters';

    /** @var CurrentValues */
    private $currentValues;
    /** @var array */
    private $selectedModifiersTree;
    /** @var array */
    private $selectedFormulaSpellParameters;
    /** @var array */
    private $selectedModifiersSpellParameters;
    /** @var array */
    private $selectedModifiersSpellTraits;
    /** @var array */
    private $selectedModifiersSpellTraitsTrapValues;
    /** @var FormulaCode|null */
    private $currentFormulaCode;
    /** @var Tables */
    private $tables;

    public function __construct(CurrentValues $currentValues, Tables $tables)
    {
        $this->currentValues = $currentValues;
        $this->tables = $tables;
    }

    public function getCurrentFormulaCode(): FormulaCode
    {
        if ($this->currentFormulaCode === null) {
            $this->currentFormulaCode = FormulaCode::findIt($this->currentValues->getCurrentValue(self::FORMULA));
        }
        return $this->currentFormulaCode;
    }

    /**
     * @return array|string[][][]
     */
    public function getCurrentModifiersTree(): array
    {
        if ($this->selectedModifiersTree !== null) {
            return $this->selectedModifiersTree;
        }
        if ($this->isFormulaChanged() || $this->currentValues->getCurrentValue(self::MODIFIERS) === null) {
            return $this->selectedModifiersTree = [];
        }

        return $this->selectedModifiersTree = $this->buildCurrentModifierValuesTree(
            (array)$this->currentValues->getCurrentValue(self::MODIFIERS)
        );
    }

    private function isFormulaChanged(): bool
    {
        return $this->getCurrentFormulaCode()->getValue() !== $this->getPreviousFormulaValue();
    }

    private function getPreviousFormulaValue(): ?string
    {
        return $this->currentValues->getCurrentValue(self::PREVIOUS_FORMULA);
    }

    private function buildCurrentModifierValuesTree(array $modifierValues): array
    {
        $modifiers = [];
        /**
         * @var string $levelToParentModifier
         * @var array|string[] $levelModifiers
         */
        foreach ($modifierValues as $levelToParentModifier => $levelModifiers) {
            [$level, $parentModifier] = explode('-', $levelToParentModifier);
            if ($level > 1
                && (!array_key_exists($level - 1, $modifiers) || !in_array($parentModifier, $modifiers[$level - 1], true))
            ) {
                continue; // skip branch without selected parent modifier (early bag end)
            }
            foreach ($levelModifiers as $levelModifier) {
                $modifiers[$level][$levelModifier] = $levelModifier;
            }
        }

        return $modifiers;
    }

    /**
     * @return array|int[]
     */
    public function getCurrentFormulaSpellParameters(): array
    {
        if ($this->selectedFormulaSpellParameters !== null) {
            return $this->selectedFormulaSpellParameters;
        }
        $selectedFormulaParameterValues = $this->currentValues->getCurrentValue(self::FORMULA_PARAMETERS);
        $this->selectedFormulaSpellParameters = [];
        if ($selectedFormulaParameterValues === null || $this->isFormulaChanged()) {
            return $this->selectedFormulaSpellParameters;
        }
        /** @var array|int[] $selectedFormulaParameterValues */
        foreach ($selectedFormulaParameterValues as $formulaParameterName => $value) {
            $formulaParameterName = $this->getFormulaNewParameterName($formulaParameterName);
            $this->selectedFormulaSpellParameters[$formulaParameterName] = ToInteger::toInteger($value);
        }

        return $this->selectedFormulaSpellParameters;
    }

    private static $oldToNewMutableSpellParameterName = [
        'power' => FormulaMutableSpellParameterCode::SPELL_POWER,
        'radius' => FormulaMutableSpellParameterCode::SPELL_RADIUS,
        'brightness' => FormulaMutableSpellParameterCode::SPELL_BRIGHTNESS,
        'attack' => FormulaMutableSpellParameterCode::SPELL_ATTACK,
        'speed' => FormulaMutableSpellParameterCode::SPELL_SPEED,
    ];

    private function getFormulaNewParameterName(string $formulaParameterName): string
    {
        return self::$oldToNewMutableSpellParameterName[$formulaParameterName] ?? $formulaParameterName;
    }

    /**
     * @return array|int[][][]
     */
    public function getCurrentModifiersSpellParametersTree(): array
    {
        if ($this->selectedModifiersSpellParameters !== null) {
            return $this->selectedModifiersSpellParameters;
        }
        $selectedModifierParameterValues = $this->currentValues->getCurrentValue(self::MODIFIER_PARAMETERS);
        $this->selectedModifiersSpellParameters = [];
        if ($selectedModifierParameterValues === null || $this->isFormulaChanged()) {
            return $this->selectedModifiersSpellParameters;
        }

        $selectedModifiers = $this->getCurrentModifiersTree();
        /** @var array|int[][][] $sameLevelParameters */
        foreach ((array)$selectedModifierParameterValues as $treeLevel => $sameLevelParameters) {
            if (!array_key_exists($treeLevel, $selectedModifiers)) {
                continue;
            }
            foreach ($sameLevelParameters as $modifierName => $modifierParameters) {
                if (!array_key_exists($modifierName, $selectedModifiers[$treeLevel])) {
                    continue;
                }
                /** @var int[] $modifierParameters */
                foreach ($modifierParameters as $parameterName => $value) {
                    $this->selectedModifiersSpellParameters[$treeLevel][$modifierName][$parameterName] = ToInteger::toInteger($value);
                }
            }
        }

        return $this->selectedModifiersSpellParameters;
    }

    /**
     * @return array|string[][][]
     */
    public function getCurrentModifiersSpellTraitValues(): array
    {
        if ($this->selectedModifiersSpellTraits !== null) {
            return $this->selectedModifiersSpellTraits;
        }
        $selectedModifierSpellTraitValues = $this->currentValues->getCurrentValue(self::MODIFIER_SPELL_TRAITS);
        if ($selectedModifierSpellTraitValues === null || $this->isFormulaChanged()) {
            return $this->selectedModifiersSpellTraits = [];
        }

        return $this->selectedModifiersSpellTraits = $this->buildSelectedModifierTraitsTree(
            (array)$selectedModifierSpellTraitValues,
            $this->getCurrentModifiersTree()
        );
    }

    /**
     * @param array $traitValues
     * @param array $selectedModifiersTree
     * @return array|string[][][]
     */
    private function buildSelectedModifierTraitsTree(array $traitValues, array $selectedModifiersTree): array
    {
        $traitsTree = [];
        /** @var array|string[] $levelTraitValues */
        foreach ($traitValues as $levelAndModifier => $levelTraitValues) {
            [$level, $modifier] = explode('-', $levelAndModifier);
            if (empty($selectedModifiersTree[$level][$modifier])) {
                continue; // skip still selected traits but without selected parent modifier
            }
            foreach ($levelTraitValues as $levelTraitValue) {
                $traitsTree[$level][$modifier][] = $levelTraitValue;
            }
        }

        return $traitsTree;
    }

    /**
     * @return array|string[]
     */
    public function getCurrentFormulaSpellTraitValues(): array
    {
        $formulaSpellTraits = $this->currentValues->getCurrentValue(self::FORMULA_SPELL_TRAITS);
        if ($formulaSpellTraits === null || $this->isFormulaChanged()) {
            return [];
        }

        return (array)$formulaSpellTraits;
    }

    /**
     * @return array|SpellTrait[]
     */
    public function getCurrentFormulaSpellTraits(): array
    {
        return $this->buildSpellTraitsTree(
            $this->getCurrentFormulaSpellTraitValues(),
            [] /* no traps for formula spell traits */
        );
    }

    /**
     * @param array $spellTraitsBranch
     * @param array $spellTraitsTrapsBranch
     * @return array|SpellTrait[]
     */
    private function buildSpellTraitsTree(array $spellTraitsBranch, array $spellTraitsTrapsBranch): array
    {
        $spellTraitsTree = [];
        foreach ($spellTraitsBranch as $index => $spellTraitsLeaf) {
            if (is_array($spellTraitsLeaf)) {
                $spellTraitsTree[$index] = $this->buildSpellTraitsTree($spellTraitsLeaf, $spellTraitsTrapsBranch[$index] ?? []);
                continue;
            }
            $spellTraitsTree[$index] = new SpellTrait(
                SpellTraitCode::getIt($spellTraitsLeaf),
                $this->tables,
                $spellTraitsTrapsBranch[$spellTraitsLeaf] ?? null
            );
        }
        return $spellTraitsTree;
    }

    /**
     * All formula direct modifiers, already selected modifiers and children of them all
     *
     * @return array|ModifierCode[][]
     */
    public function getPossibleModifierCombinations(): array
    {
        $formulaModifiersTree = $this->getFormulaDirectModifierCombinations();
        $possibleModifiersTree = $this->buildPossibleModifiersTree($this->getCurrentModifiersFlatArrayValues());
        $possibleModifiersTree[''] = $formulaModifiersTree;
        return $possibleModifiersTree;
    }

    /**
     * @return array|ModifierCode[]
     */
    private function getFormulaDirectModifierCombinations(): array
    {
        $formulaModifierCodesTree = [];
        foreach ($this->tables->getFormulasTable()->getModifierCodes($this->getCurrentFormulaCode()) as $modifierCode) {
            $formulaModifierCodesTree[$modifierCode->getValue()] = $modifierCode; // as a child modifier
        }
        return $formulaModifierCodesTree;
    }

    private function getCurrentModifiersFlatArrayValues(): array
    {
        $selectedModifierValues = [];
        foreach ($this->getCurrentModifiersTree() as $level => $selectedLevelModifiers) {
            /** @var array|string[] $selectedLevelModifiers */
            foreach ($selectedLevelModifiers as $selectedLevelModifier) {
                $selectedModifierValues[] = $selectedLevelModifier;
            }
        }

        return $selectedModifierValues;
    }

    private function buildPossibleModifiersTree(array $modifierValues, array $processedModifiers = []): array
    {
        $modifiers = [];
        $childModifierValues = [];
        foreach ($modifierValues as $modifierValue) {
            if (!array_key_exists($modifierValue, $processedModifiers)) { // otherwise skip already processed relating modifiers
                $modifierCode = ModifierCode::getIt($modifierValue);
                foreach ($this->tables->getModifiersTable()->getChildModifierCodes($modifierCode) as $childModifier) {
                    // by-related-modifier-indexed flat array
                    $modifiers[$modifierValue][$childModifier->getValue()] = $childModifier;
                    $childModifierValues[] = $childModifier->getValue();
                }
            }
        }
        $childModifiersToAdd = array_diff($childModifierValues, $modifierValues); // not yet processed in current loop
        if (count($childModifiersToAdd) > 0) {
            // flat array
            foreach ($this->buildPossibleModifiersTree($childModifiersToAdd, $modifiers) as $modifierValueToAdd => $modifierToAdd) {
                $modifiers[$modifierValueToAdd] = $modifierToAdd;
            }
        }

        return $modifiers;
    }

    /**
     * @return Formula
     */
    public function getCurrentFormula(): Formula
    {
        return new Formula(
            $this->getCurrentFormulaCode(),
            $this->tables,
            $this->getCurrentFormulaSpellParameters(), // formula spell parameter changes
            $this->getCurrentModifiers(),
            $this->getCurrentFormulaSpellTraits()
        );
    }

    /**
     * @return array|Modifier[]|Modifier[][] ...
     */
    private function getCurrentModifiers(): array
    {
        return $this->buildCurrentModifiersTree(
            $this->getCurrentModifiersTree(),
            $this->getCurrentModifiersSpellParametersTree(),
            $this->getCurrentModifiersSpellTraitsTree()
        );
    }

    /**
     * @return array|SpellTrait[]
     */
    private function getCurrentModifiersSpellTraitsTree(): array
    {
        return $this->buildSpellTraitsTree(
            $this->getCurrentModifiersSpellTraitValues(),
            $this->getCurrentModifiersSpellTraitsTrapValues()
        );
    }

    public function getCurrentModifiersSpellTraitsTrapValues(): array
    {
        if ($this->selectedModifiersSpellTraitsTrapValues !== null) {
            return $this->selectedModifiersSpellTraitsTrapValues;
        }
        $selectedModifierSpellTraitTrapValues = $this->currentValues->getCurrentValue(self::MODIFIER_SPELL_TRAIT_TRAPS);
        if ($selectedModifierSpellTraitTrapValues === null || $this->isFormulaChanged()) {
            return $this->selectedModifiersSpellTraitsTrapValues = [];
        }
        $this->selectedModifiersSpellTraitsTrapValues = $this->buildSelectedModifiersSpellTraitsTrapValuesTree(
            (array)$selectedModifierSpellTraitTrapValues,
            $this->getCurrentModifiersSpellTraitValues()
        );

        return $this->selectedModifiersSpellTraitsTrapValues;
    }

    /**
     * @param array $traitTrapValues
     * @param array $selectedSpellTraitsTree
     * @return array|string[][][]
     */
    private function buildSelectedModifiersSpellTraitsTrapValuesTree(array $traitTrapValues, array $selectedSpellTraitsTree): array
    {
        $traitsTrapsTree = [];
        /** @var array|string[] $levelTraitValues */
        foreach ($traitTrapValues as $levelModifierAndTrait => $levelTraitValue) {
            [$level, $modifier, $trait] = explode('-', $levelModifierAndTrait);
            if (!in_array($trait, $selectedSpellTraitsTree[$level][$modifier] ?? [], true)) {
                continue; // skip still selected trait trap but without selected parent trait
            }
            $traitsTrapsTree[$level][$modifier][$trait] = ToInteger::toInteger($levelTraitValue);
        }

        return $traitsTrapsTree;
    }

    /**
     * @param array $selectedModifierValues
     * @param array $selectedModifierParameterValues
     * @param array $selectedModifierSpellTraits
     * @return array
     */
    private function buildCurrentModifiersTree(
        array $selectedModifierValues,
        array $selectedModifierParameterValues,
        array $selectedModifierSpellTraits
    ): array
    {
        $modifierValuesWithSpellTraits = [];
        foreach ($selectedModifierValues as $index => $selectedModifiersBranch) {
            if (is_array($selectedModifiersBranch)) {
                $modifierValuesWithSpellTraits[$index] = $this->buildCurrentModifiersTree(
                    $selectedModifiersBranch,
                    $selectedModifierParameterValues[$index] ?? [],
                    $selectedModifierSpellTraits[$index] ?? []
                );
                continue;
            }
            $modifierValuesWithSpellTraits[$index] = new Modifier(
                ModifierCode::getIt($selectedModifiersBranch),
                $this->tables,
                $selectedModifierParameterValues[$selectedModifiersBranch] ?? [],
                $selectedModifierSpellTraits[$selectedModifiersBranch] ?? []
            );
        }

        return $modifierValuesWithSpellTraits;
    }

    /**
     * @param FormulaCode $formulaCode
     * @param string $language
     * @return array|string[]
     */
    public function getFormulaFormNames(FormulaCode $formulaCode, string $language): array
    {
        $formNames = [];
        foreach ($this->tables->getFormulasTable()->getFormCodes($formulaCode) as $formCode) {
            $formNames[] = $formCode->translateTo($language);
        }

        return $formNames;
    }

    public function createModifierInputIndex(array $modifiersChain): string
    {
        $wrapped = array_map(
            function (string $chainPart) {
                return "[$chainPart]";
            },
            $modifiersChain
        );
        return implode($wrapped);
    }

    public function createSpellTraitInputIndex(array $modifiersChain, string $spellTraitName): string
    {
        $wrapped = array_map(
            function (string $chainPart) {
                return "[!$chainPart!]"; // wrapped by ! to avoid conflict with same named spell trait on long chain
            },
            $modifiersChain
        );
        $wrapped[] = "[{$spellTraitName}]";
        return implode($wrapped);
    }

    /**
     * @param ModifierCode $modifierCode
     * @param string $language
     * @return array|string[]
     */
    public function getModifierFormNames(ModifierCode $modifierCode, string $language): array
    {
        $formNames = [];
        foreach ($this->tables->getModifiersTable()->getFormCodes($modifierCode) as $formCode) {
            $formNames[] = $formCode->translateTo($language);
        }
        return $formNames;
    }

    public function isModifierSelected(string $modifierValue, array $selectedModifiers, int $treeLevel): bool
    {
        $levelSelection = $selectedModifiers[$treeLevel] ?? false;
        if ($levelSelection === false) {
            return false;
        }
        $selection = $levelSelection[$modifierValue] ?? false;
        if ($selection === false) {
            return false;
        }
        return $selection === $modifierValue /* bag end */ || is_array($selection); /* still traversing on the tree */
    }

    public function formatNumber(NumberInterface $number): string
    {
        return $number->getValue() >= 0
            ? '+' . $number->getValue()
            : (string)$number->getValue();
    }
}