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
     * @return array|ModifierCode[][]
     */
    public function getSelectedModifierCombinations(): array
    {
        $selectedModifierIndexes = $this->getSelectedModifierIndexes();
        if (count($selectedModifierIndexes) === 0) {
            return [];
        }

        return $this->buildPossibleModifiersTree($selectedModifierIndexes);
    }

    public function getSelectedModifierIndexes(): array
    {
        if (empty($_GET['modifiers']) || $this->getSelectedFormula()->getValue() !== $this->getPreviouslySelectedFormulaValue()) {
            return [];
        }

        return $this->buildSelectedModifiersTree((array)$_GET['modifiers']);
    }

    private function buildPossibleModifiersTree(array $modifierValues): array
    {
        $modifiers = [];
        foreach ($modifierValues as $modifierValue => $relatedModifierValues) {
            if (!array_key_exists($modifierValue, $modifiers)) { // otherwise skip already processed relating modifiers
                $modifierCode = ModifierCode::getIt($modifierValue);
                /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                foreach ($this->modifiersTable->getChildModifiers($modifierCode) as $relatedModifierCode) {
                    // by-related-modifier-indexed flat array
                    $modifiers[$modifierValue][$relatedModifierCode->getValue()] = $relatedModifierCode;
                }
            }
            // tree structure
            foreach ($this->buildPossibleModifiersTree($relatedModifierValues) as $relatedModifierValue => $relatedModifiers) {
                // into flat array
                $modifiers[$relatedModifierValue] = $relatedModifiers; // can overrides previously set (would be the very same so no harm)
            }
        }

        return $modifiers;
    }

    private function buildSelectedModifiersTree(array $modifierValues): array
    {
        $modifiers = [];
        foreach ($modifierValues as $modifierValue => $linkedModifiers) {
            if (is_array($linkedModifiers)) {
                $modifiers[$modifierValue] = $this->buildSelectedModifiersTree($linkedModifiers); // tree structure
            } else {
                $modifiers[$modifierValue] = []; // dead end
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

    /**
     * @return array|ModifierCode[]
     */
    public function getSelectedModifierCodes(): array
    {
        return $this->keysToModifiers($this->getSelectedModifierIndexes());
    }

    private function keysToModifiers(array $modifierNamesAsKeys): array
    {
        $modifiers = [];
        foreach ($modifierNamesAsKeys as $modifierName => $childModifierNamesAsKeys) {
            $modifiers[] = ModifierCode::getIt($modifierName);
            if (is_array($childModifierNamesAsKeys)) {
                foreach ($this->keysToModifiers($childModifierNamesAsKeys) as $childModifier) {
                    $modifiers[] = $childModifier;
                }
            }
        }

        return $modifiers;
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
    public function getSelectedFormulaSpellTraitIndexes(): array
    {
        if (empty($_GET['formulaSpellTraits']) || $this->getSelectedFormula()->getValue() !== $this->getPreviouslySelectedFormulaValue()) {
            return [];
        }

        return array_keys($_GET['formulaSpellTraits']);
    }

    /**
     * @return array|SpellTrait[]
     */
    public function getSelectedSpellTraitCodes(): array
    {
        $selectedSpellTraitCodes = [];
        foreach ($this->getSelectedFormulaSpellTraitIndexes() as $selectedSpellTraitValue) {
            $selectedSpellTraitCodes[] = SpellTraitCode::getIt($selectedSpellTraitValue);
        }

        return $selectedSpellTraitCodes;
    }
}