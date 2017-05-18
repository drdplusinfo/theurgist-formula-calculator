<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Codes\ModifierCode;
use DrdPlus\Theurgist\Codes\SpellTraitCode;
use DrdPlus\Theurgist\Spells\Formula;
use DrdPlus\Theurgist\Spells\FormulasTable;
use DrdPlus\Theurgist\Spells\Modifier;
use DrdPlus\Theurgist\Spells\ModifiersTable;
use DrdPlus\Theurgist\Spells\SpellTrait;
use DrdPlus\Theurgist\Spells\SpellTraitsTable;
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;

class IndexController extends StrictObject
{
    /** @var FormulasTable */
    private $formulasTable;
    /** @var ModifiersTable */
    private $modifiersTable;
    /** @var FormulaCode */
    private $selectedFormulaCode;
    /** @var SpellTraitsTable */
    private $spellTraitsTable;
    /** @var array */
    private $selectedFormulaSpellParameters;
    /** @var array */
    private $selectedModifiersSpellTraits;
    /** @var array */
    private $selectedModifiersSpellTraitsTrapValues;

    /**
     * @param FormulasTable $formulasTable
     * @param ModifiersTable $modifiersTable
     * @param SpellTraitsTable $spellTraitsTable
     */
    public function __construct(
        FormulasTable $formulasTable,
        ModifiersTable $modifiersTable,
        SpellTraitsTable $spellTraitsTable
    )
    {
        $this->formulasTable = $formulasTable;
        $this->modifiersTable = $modifiersTable;
        $this->spellTraitsTable = $spellTraitsTable;
    }

    /**
     * @return FormulaCode
     */
    private function getSelectedFormulaCode(): FormulaCode
    {
        if ($this->selectedFormulaCode === null) {
            $this->selectedFormulaCode = FormulaCode::getIt($_GET['formula'] ?? current(FormulaCode::getPossibleValues()));
        }

        return $this->selectedFormulaCode;
    }

    /**
     * @param FormulaCode $formulaCode
     * @param string $language
     * @return array|string[]
     */
    public function getFormulaFormNames(FormulaCode $formulaCode, string $language): array
    {
        $formNames = [];
        foreach ($this->formulasTable->getForms($formulaCode) as $formCode) {
            $formNames[] = $formCode->translateTo($language);
        }

        return $formNames;
    }

    /**
     * All formula direct modifiers, already selected modifiers and children of them all
     *
     * @return array|ModifierCode[][]
     */
    public function getPossibleModifierCombinations(): array
    {
        $formulaModifiersTree = $this->getFormulaDirectModifierCombinations();
        $possibleModifiersTree = $this->buildPossibleModifiersTree($this->getSelectedModifiersFlatArrayValues());
        $possibleModifiersTree[''] = $formulaModifiersTree;

        return $possibleModifiersTree;
    }

    /**
     * @return array|ModifierCode[]
     */
    private function getFormulaDirectModifierCombinations(): array
    {
        $formulaModifierCodesTree = [];
        /** @var array|ModifierCode[] $childModifiers */
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        foreach ($this->formulasTable->getModifierCodes($this->getSelectedFormulaCode()) as $modifierCode) {
            $formulaModifierCodesTree[$modifierCode->getValue()] = $modifierCode; // as a child modifier
        }

        return $formulaModifierCodesTree;
    }

    private function getSelectedModifiersFlatArrayValues(): array
    {
        $selectedModifierValues = [];
        foreach ($this->getSelectedModifiersTree() as $level => $selectedLevelModifiers) {
            /** @var array|string[] $selectedLevelModifiers */
            foreach ($selectedLevelModifiers as $selectedLevelModifier) {
                $selectedModifierValues[] = $selectedLevelModifier;
            }
        }

        return $selectedModifierValues;
    }

    private $selectedModifiersTree;

    public function getSelectedModifiersTree(): array
    {
        if ($this->selectedModifiersTree !== null) {
            return $this->selectedModifiersTree;
        }
        if (empty($_GET['modifiers']) || $this->isFormulaChanged()) {
            return $this->selectedModifiersTree = [];
        }

        return $this->selectedModifiersTree = $this->buildSelectedModifierValuesTree((array)$_GET['modifiers']);
    }

    private function isFormulaChanged(): bool
    {
        return $this->getSelectedFormulaCode()->getValue() !== $this->getPreviouslySelectedFormulaValue();
    }

    private function buildPossibleModifiersTree(array $modifierValues, array $processedModifiers = []): array
    {
        $modifiers = [];
        $childModifierValues = [];
        foreach ($modifierValues as $modifierValue) {
            if (!array_key_exists($modifierValue, $processedModifiers)) { // otherwise skip already processed relating modifiers
                $modifierCode = ModifierCode::getIt($modifierValue);
                /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                foreach ($this->modifiersTable->getChildModifiers($modifierCode) as $childModifier) {
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

    private function buildSelectedModifierValuesTree(array $modifierValues): array
    {
        $modifiers = [];
        /**
         * @var string $levelToParentModifier
         * @var array|string[] $levelModifiers
         */
        foreach ($modifierValues as $levelToParentModifier => $levelModifiers) {
            list($level, $parentModifier) = explode('-', $levelToParentModifier);
            if (count($modifiers) > 0
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
     * @return string|null
     */
    private function getPreviouslySelectedFormulaValue()
    {
        return (string)$_GET['previousFormula'] ?? null;
    }

    public function getSelectedFormula(): Formula
    {
        return new Formula(
            $this->getSelectedFormulaCode(),
            $this->formulasTable,
            $this->getSelectedFormulaSpellParameters(), // formula spell parameter changes
            $this->getSelectedModifiers(),
            $this->getSelectedFormulaSpellTraits()
        );
    }

    /**
     * @return array|int[]
     */
    public function getSelectedFormulaSpellParameters(): array
    {
        if ($this->selectedFormulaSpellParameters !== null) {
            return $this->selectedFormulaSpellParameters;
        }
        if (empty($_GET['formulaParameters']) || $this->isFormulaChanged()) {
            return $this->selectedFormulaSpellParameters = [];
        }
        $this->selectedFormulaSpellParameters = [];
        /** @var array|string[][] $_GET */
        foreach ($_GET['formulaParameters'] as $formulaParameterName => $value) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $this->selectedFormulaSpellParameters[$formulaParameterName] = ToInteger::toInteger($value);
        }

        return $this->selectedFormulaSpellParameters;
    }

    /**
     * @return array|Modifier[]
     */
    private function getSelectedModifiers(): array
    {
        return $this->buildSelectedModifiersTree(
            $this->getSelectedModifiersTree(),
            $this->getSelectedModifiersSpellTraitsTree()
        );
    }

    /**
     * @return array|SpellTrait[]
     */
    private function getSelectedModifiersSpellTraitsTree(): array
    {
        return $this->buildSpellTraitsTree(
            $this->getSelectedModifiersSpellTraitValues(),
            $this->getSelectedModifiersSpellTraitsTrapValues()
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
                $this->spellTraitsTable,
                $spellTraitsTrapsBranch[$spellTraitsLeaf] // TODO wrong, we are providing final value, not change
            );
        }

        return $spellTraitsTree;
    }

    private function buildSelectedModifiersTree(array $selectedModifierValues, array $selectedModifierSpellTraits): array
    {
        $modifierValuesWithSpellTraits = [];
        foreach ($selectedModifierValues as $index => $selectedModifiersBranch) {
            if (is_array($selectedModifiersBranch)) {
                $modifierValuesWithSpellTraits[$index] = $this->buildSelectedModifiersTree(
                    $selectedModifiersBranch,
                    $selectedModifierSpellTraits[$index] ?? []
                );
                continue;
            }
            $modifierValuesWithSpellTraits[$index] = new Modifier(
                ModifierCode::getIt($selectedModifiersBranch),
                $this->modifiersTable,
                [], // modifier spell parameter changes
                $selectedModifierSpellTraits[$selectedModifiersBranch] ?? []
            );
        }

        return $modifierValuesWithSpellTraits;
    }

    /**
     * @return array|SpellTrait[]
     */
    public function getSelectedFormulaSpellTraits(): array
    {
        return $this->buildSpellTraitsTree(
            $this->getSelectedFormulaSpellTraitCodes(),
            [] /* no traps for formula spell traits */
        );
    }

    /**
     * @return array|SpellTrait[]
     */
    private function getSelectedFormulaSpellTraitCodes(): array
    {
        $selectedFormulaSpellTraitCodes = [];
        foreach ($this->getSelectedFormulaSpellTraitValues() as $selectedFormulaSpellTrait) {
            $selectedFormulaSpellTraitCodes[] = SpellTraitCode::getIt($selectedFormulaSpellTrait);
        }

        return $selectedFormulaSpellTraitCodes;
    }

    /**
     * @param array $modifiersChain
     * @return string
     */
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

    /**
     * @param array $modifiersChain
     * @param string $spellTraitName
     * @return string
     */
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
        foreach ($this->modifiersTable->getForms($modifierCode) as $formCode) {
            $formNames[] = $formCode->translateTo($language);
        }

        return $formNames;
    }

    /**
     * @return array|string[]
     */
    public function getSelectedFormulaSpellTraitValues(): array
    {
        if (empty($_GET['formulaSpellTraits']) || $this->isFormulaChanged()) {
            return [];
        }

        return $_GET['formulaSpellTraits'];
    }

    private function toFlatArray(array $values): array
    {
        $flat = [];
        foreach ($values as $index => $value) {
            if (is_array($value)) {
                $flat[] = $index;
                foreach ($this->toFlatArray($value) as $subItem) {
                    $flat[] = $subItem;
                }
            } else {
                $flat[] = $value;
            }
        }

        return $flat;
    }

    private function getBagEnds(array $values): array
    {
        $bagEnds = [];
        foreach ($values as $value) {
            if (is_array($value)) {
                foreach ($this->getBagEnds($value) as $subItem) {
                    $bagEnds[] = $subItem;
                }
            } else {
                $bagEnds[] = $value;
            }
        }

        return $bagEnds;
    }

    /**
     * @return array|string[][][]
     */
    public function getSelectedModifiersSpellTraitValues(): array
    {
        if ($this->selectedModifiersSpellTraits !== null) {
            return $this->selectedModifiersSpellTraits;
        }
        if (empty($_GET['modifierSpellTraits']) || $this->isFormulaChanged()) {
            return $this->selectedModifiersSpellTraits = [];
        }

        return $this->selectedModifiersSpellTraits = $this->buildSelectedModifierTraitsTree(
            (array)$_GET['modifierSpellTraits'],
            $this->getSelectedModifiersTree()
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
            list($level, $modifier) = explode('-', $levelAndModifier);
            if (empty($selectedModifiersTree[$level][$modifier])) {
                continue; // skip still selected traits but without selected parent modifier
            }
            foreach ($levelTraitValues as $levelTraitValue) {
                $traitsTree[$level][$modifier][] = $levelTraitValue;
            }
        }

        return $traitsTree;
    }

    public function getSelectedModifiersSpellTraitsTrapValues(): array
    {
        if ($this->selectedModifiersSpellTraitsTrapValues !== null) {
            return $this->selectedModifiersSpellTraitsTrapValues;
        }
        if (empty($_GET['modifierSpellTraitTraps']) || $this->isFormulaChanged()) {
            return $this->selectedModifiersSpellTraitsTrapValues = [];
        }

        return $this->selectedModifiersSpellTraitsTrapValues = $this->buildSelectedModifiersSpellTraitsTrapValuesTree(
            (array)$_GET['modifierSpellTraitTraps'],
            $this->getSelectedModifiersSpellTraitValues()
        );
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
            list($level, $modifier, $trait) = explode('-', $levelModifierAndTrait);
            if (!in_array($trait, $selectedSpellTraitsTree[$level][$modifier] ?? [], true)) {
                continue; // skip still selected trait trap but without selected parent trait
            }
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $traitsTrapsTree[$level][$modifier][$trait] = ToInteger::toInteger($levelTraitValue);
        }

        return $traitsTrapsTree;
    }
}