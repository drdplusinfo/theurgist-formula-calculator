<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Codes\ModifierCode;
use DrdPlus\Theurgist\Codes\SpellTraitCode;
use DrdPlus\Theurgist\Formulas\CastingParameters\SpellTrait;
use DrdPlus\Theurgist\Formulas\FormulasTable;
use DrdPlus\Theurgist\Formulas\ModifiersTable;
use Granam\Strict\Object\StrictObject;

class IndexController extends StrictObject
{
    /**
     * @var FormulasTable
     */
    private $formulasTable;
    /**
     * @var ModifiersTable
     */
    private $modifiersTable;
    /**
     * @var FormulaCode
     */
    private $selectedFormula;

    /**
     * @param FormulasTable $formulasTable
     * @param ModifiersTable $modifiersTable
     */
    public function __construct(FormulasTable $formulasTable, ModifiersTable $modifiersTable)
    {
        $this->formulasTable = $formulasTable;
        $this->modifiersTable = $modifiersTable;
    }

    /**
     * @return FormulaCode
     */
    public function getSelectedFormula(): FormulaCode
    {
        if ($this->selectedFormula === null) {
            $this->selectedFormula = FormulaCode::getIt($_GET['formula'] ?? current(FormulaCode::getPossibleValues()));
        }

        return $this->selectedFormula;
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
        $possibleModifiersTree = $this->buildPossibleModifiersTree($this->getSelectedModifierValues());
        $possibleModifiersTree[''] = $formulaModifiersTree;

        return $possibleModifiersTree;
    }

    private function getFormulaDirectModifierCombinations(): array
    {
        $formulaModifiersTree = [];
        /** @var array|ModifierCode[] $childModifiers */
        foreach ($this->formulasTable->getModifiers($this->getSelectedFormula()) as $modifier) {
            $formulaModifiersTree[$modifier->getValue()] = $modifier; // as a child modifier
        }

        return $formulaModifiersTree;
    }

    private function getSelectedModifierValues(): array
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
        if (empty($_GET['modifiers']) || $this->getSelectedFormula()->getValue() !== $this->getPreviouslySelectedFormulaValue()) {
            return $this->selectedModifiersTree = [];
        }

        return $this->selectedModifiersTree = $this->buildSelectedModifiersTree((array)$_GET['modifiers']);
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

    private function buildSelectedModifiersTree(array $modifierValues): array
    {
        $modifiers = [];
        $expectedParentModifiers = [''];
        /**
         * @var string $levelToParentModifier
         * @var array|string[] $levelModifiers
         */
        foreach ($modifierValues as $levelToParentModifier => $levelModifiers) {
            list($level, $parentModifier) = explode('-', $levelToParentModifier);
            if (!in_array($parentModifier, $expectedParentModifiers, true)) {
                continue; // skip branch without selected parent modifier (early bag end)
            }
            if (array_key_exists($level, $modifiers)) {
                throw new \LogicException("Level {$level} of modifiers tree has been already processed");
            }
            $modifiers[$level] = [];
            foreach ($levelModifiers as $levelModifier) {
                $modifiers[$level][$levelModifier] = $levelModifier;
            }
            $expectedParentModifiers = $modifiers[$level];
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

    /**
     * @return array|ModifierCode[]
     */
    public function getSelectedModifierCodes(): array
    {
        return array_map(
            function (string $selectedModifierValue) {
                return ModifierCode::getIt($selectedModifierValue);
            },
            $this->getSelectedModifierValues()
        );
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
    public function getSelectedFormulaSpellTraits(): array
    {
        if (empty($_GET['formulaSpellTraits']) || $this->getSelectedFormula()->getValue() !== $this->getPreviouslySelectedFormulaValue()) {
            return [];
        }

        return $_GET['formulaSpellTraits'];
    }

    /**
     * @return array|SpellTrait[]
     */
    public function getSelectedSpellTraitCodes(): array
    {
        $selectedSpellTraitCodes = [];
        foreach ($this->getSelectedFormulaSpellTraits() as $selectedFormulaSpellTrait) {
            $selectedSpellTraitCodes['0'] = SpellTraitCode::getIt($selectedFormulaSpellTrait);
        }
        foreach ($this->getSelectedModifiersSpellTraits() as $level => $selectedModifiersLevelSpellTrait) {
            /** @var array|string[][] $selectedModifiersLevelSpellTrait */
            foreach ($selectedModifiersLevelSpellTrait as $levelSpellTraits) {
                /** @var array|string[] $levelSpellTraits */
                foreach ($levelSpellTraits as $modifier => $modifierSpellTrait) {
                    $selectedSpellTraitCodes[$level][] = SpellTraitCode::getIt($modifierSpellTrait);
                }
            }
        }

        return $selectedSpellTraitCodes;
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

    private $selectedModifiersSpellTraits;

    /**
     * @return array|string[][][]
     */
    public function getSelectedModifiersSpellTraits(): array
    {
        if ($this->selectedModifiersSpellTraits !== null) {
            return $this->selectedModifiersSpellTraits;
        }
        if (empty($_GET['modifierSpellTraits'])
            || $this->getSelectedFormula()->getValue() !== $this->getPreviouslySelectedFormulaValue()
        ) {
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
     * @return array|string[][]
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

}