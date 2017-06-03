<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Tables\Measurements\BaseOfWounds\BaseOfWoundsTable;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
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

class Controller extends StrictObject
{

    const DELETE_THEURGIST_CONFIGURATOR_HISTORY = 'delete_theurgist_configurator_history';
    const THEURGIST_CONFIGURATOR_HISTORY = 'theurgist_configurator_history';
    const THEURGIST_CONFIGURATOR_HISTORY_TOKEN = 'theurgist_configurator_history_token';
    const REMEMBER = 'remember';
    const FORGOT_FIGHT = 'forgot_fight';
    const FORMULA = 'formula';
    const MODIFIERS = 'modifiers';
    const PREVIOUS_FORMULA = 'previous_formula';
    const FORMULA_PARAMETERS = 'formula_parameters';
    const FORMULA_SPELL_TRAITS = 'formula_spell_traits';
    const MODIFIER_SPELL_TRAITS = 'modifier_spell_traits';
    const MODIFIER_SPELL_TRAIT_TRAPS = 'modifier_spell_trait_traps';
    const MODIFIER_PARAMETERS = 'modifier_parameters';

    /** @var array */
    private $history = [];
    /** @var FormulasTable */
    private $formulasTable;
    /** @var ModifiersTable */
    private $modifiersTable;
    /** @var BaseOfWoundsTable */
    private $distanceTable;
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
    /** @var array */
    private $selectedModifiersSpellParameters;

    /**
     * @param FormulasTable $formulasTable
     * @param ModifiersTable $modifiersTable
     * @param SpellTraitsTable $spellTraitsTable
     * @param DistanceTable $distanceTable
     */
    public function __construct(
        FormulasTable $formulasTable,
        ModifiersTable $modifiersTable,
        SpellTraitsTable $spellTraitsTable,
        DistanceTable $distanceTable
    )
    {
        $this->formulasTable = $formulasTable;
        $this->modifiersTable = $modifiersTable;
        $this->spellTraitsTable = $spellTraitsTable;
        $this->distanceTable = $distanceTable;

        if (!empty($_POST[self::DELETE_THEURGIST_CONFIGURATOR_HISTORY])) {
            $this->deleteHistory();
            header('Location: /', true, 301);
            exit;
        }
        $afterYear = (new \DateTime('+ 1 year'))->getTimestamp();
        if (!empty($_GET)) {
            if (!empty($_GET[self::REMEMBER])) {
                $this->setCookie(self::FORGOT_FIGHT, null, $afterYear);
                $this->setCookie(self::THEURGIST_CONFIGURATOR_HISTORY, serialize($_GET), $afterYear);
                $this->setCookie(self::THEURGIST_CONFIGURATOR_HISTORY_TOKEN, md5_file(__FILE__), $afterYear);
            } else {
                $this->deleteHistory();
                $this->setCookie(self::FORGOT_FIGHT, 1, $afterYear);
            }
        } elseif (!$this->cookieHistoryIsValid()) {
            $this->deleteHistory();
        }
        if (!empty($_COOKIE[self::THEURGIST_CONFIGURATOR_HISTORY])) {
            $this->history = unserialize($_COOKIE[self::THEURGIST_CONFIGURATOR_HISTORY], ['allowed_classes' => []]);
            if (!is_array($this->history)) {
                $this->history = [];
            }
        }
    }

    private function deleteHistory()
    {
        $this->setCookie(self::THEURGIST_CONFIGURATOR_HISTORY_TOKEN, null);
        $this->setCookie(self::THEURGIST_CONFIGURATOR_HISTORY, null);
    }

    private function setCookie(string $name, $value, int $expire = 0)
    {
        setcookie(
            $name,
            $value,
            $expire,
            '/',
            '',
            !empty($_SERVER['HTTPS']), // secure only ?
            true // http only
        );
        $_COOKIE[$name] = $value;
    }

    private function cookieHistoryIsValid(): bool
    {
        return !empty($_COOKIE[self::THEURGIST_CONFIGURATOR_HISTORY_TOKEN])
            && $_COOKIE[self::THEURGIST_CONFIGURATOR_HISTORY_TOKEN] === md5_file(__FILE__);
    }

    public function shouldRemember(): bool
    {
        return empty($_COOKIE[self::FORGOT_FIGHT]);
    }

    /**
     * @return FormulaCode
     */
    private function getSelectedFormulaCode(): FormulaCode
    {
        if ($this->selectedFormulaCode === null) {
            $this->selectedFormulaCode = FormulaCode::getIt(
                $this->getValueFromRequest(self::FORMULA) ?? current(FormulaCode::getPossibleValues())
            );
        }

        return $this->selectedFormulaCode;
    }

    /**
     * @param string $name
     * @return mixed
     */
    private function getValueFromRequest(string $name)
    {
        if (array_key_exists($name, $_GET)) {
            return $_GET[$name];
        }
        if (array_key_exists($name, $this->history) && $this->cookieHistoryIsValid()) {
            return $this->history[$name];
        }

        return null;
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

    /**
     * @return array|string[][][]
     */
    public function getSelectedModifiersTree(): array
    {
        if ($this->selectedModifiersTree !== null) {
            return $this->selectedModifiersTree;
        }
        if ($this->getValueFromRequest(self::MODIFIERS) === null || $this->isFormulaChanged()) {
            return $this->selectedModifiersTree = [];
        }

        return $this->selectedModifiersTree = $this->buildSelectedModifierValuesTree(
            (array)$this->getValueFromRequest(self::MODIFIERS)
        );
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
     * @return string|null
     */
    private function getPreviouslySelectedFormulaValue()
    {
        return $this->getValueFromRequest(self::PREVIOUS_FORMULA);
    }

    public function getSelectedFormula(): Formula
    {
        return new Formula(
            $this->getSelectedFormulaCode(),
            $this->formulasTable,
            $this->distanceTable,
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
        $selectedFormulaParameterValues = $this->getValueFromRequest(self::FORMULA_PARAMETERS);
        if ($selectedFormulaParameterValues === null || $this->isFormulaChanged()) {
            return $this->selectedFormulaSpellParameters = [];
        }
        $this->selectedFormulaSpellParameters = [];
        /** @var array|int[] $selectedFormulaParameterValues */
        foreach ($selectedFormulaParameterValues as $formulaParameterName => $value) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $this->selectedFormulaSpellParameters[$formulaParameterName] = ToInteger::toInteger($value);
        }

        return $this->selectedFormulaSpellParameters;
    }

    /**
     * @return array|Modifier[]|Modifier[][] ...
     */
    private function getSelectedModifiers(): array
    {
        return $this->buildSelectedModifiersTree(
            $this->getSelectedModifiersTree(),
            $this->getSelectedModifiersSpellParametersTree(),
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
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $spellTraitsTree[$index] = new SpellTrait(
                SpellTraitCode::getIt($spellTraitsLeaf),
                $this->spellTraitsTable,
                $spellTraitsTrapsBranch[$spellTraitsLeaf] ?? null
            );
        }

        return $spellTraitsTree;
    }

    private function buildSelectedModifiersTree(
        array $selectedModifierValues,
        array $selectedModifierParameterValues,
        array $selectedModifierSpellTraits
    ): array
    {
        $modifierValuesWithSpellTraits = [];
        foreach ($selectedModifierValues as $index => $selectedModifiersBranch) {
            if (is_array($selectedModifiersBranch)) {
                $modifierValuesWithSpellTraits[$index] = $this->buildSelectedModifiersTree(
                    $selectedModifiersBranch,
                    $selectedModifierParameterValues[$index] ?? [],
                    $selectedModifierSpellTraits[$index] ?? []
                );
                continue;
            }
            $modifierValuesWithSpellTraits[$index] = new Modifier(
                ModifierCode::getIt($selectedModifiersBranch),
                $this->modifiersTable,
                $selectedModifierParameterValues[$selectedModifiersBranch] ?? [],
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
            $this->getSelectedFormulaSpellTraitValues(),
            [] /* no traps for formula spell traits */
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
    public function getSelectedFormulaSpellTraitValues(): array
    {
        $formulaSpellTraits = $this->getValueFromRequest(self::FORMULA_SPELL_TRAITS);
        if ($formulaSpellTraits === null || $this->isFormulaChanged()) {
            return [];
        }

        return (array)$formulaSpellTraits;
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
        $selectedModifierSpellTraitValues = $this->getValueFromRequest(self::MODIFIER_SPELL_TRAITS);
        if ($selectedModifierSpellTraitValues === null || $this->isFormulaChanged()) {
            return $this->selectedModifiersSpellTraits = [];
        }

        return $this->selectedModifiersSpellTraits = $this->buildSelectedModifierTraitsTree(
            (array)$selectedModifierSpellTraitValues,
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
        $selectedModifierSpellTraitTrapValues = $this->getValueFromRequest(self::MODIFIER_SPELL_TRAIT_TRAPS);
        if ($selectedModifierSpellTraitTrapValues === null || $this->isFormulaChanged()) {
            return $this->selectedModifiersSpellTraitsTrapValues = [];
        }

        return $this->selectedModifiersSpellTraitsTrapValues = $this->buildSelectedModifiersSpellTraitsTrapValuesTree(
            (array)$selectedModifierSpellTraitTrapValues,
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

    public function getSelectedModifiersSpellParametersTree(): array
    {
        if ($this->selectedModifiersSpellParameters !== null) {
            return $this->selectedModifiersSpellParameters;
        }
        $selectedModifierParameterValues = $this->getValueFromRequest(self::MODIFIER_PARAMETERS);
        if ($selectedModifierParameterValues === null || $this->isFormulaChanged()) {
            return $this->selectedModifiersSpellParameters = [];
        }

        $this->selectedModifiersSpellParameters = [];
        $selectedModifiers = $this->getSelectedModifiersTree();
        /** @var array|int[][][] $sameLevelParameters */
        foreach ((array)$selectedModifierParameterValues as $treeLevel => $sameLevelParameters) {
            if (!array_key_exists($treeLevel, $selectedModifiers)) {
                continue;
            }
            foreach ($sameLevelParameters as $modifierName => $modifierParameters) {
                if (!array_key_exists($modifierName, $selectedModifiers[$treeLevel])) {
                    continue;
                }
                foreach ($modifierParameters as $parameterName => $value) {
                    $this->selectedModifiersSpellParameters[$treeLevel][$modifierName][$parameterName] = ToInteger::toInteger($value);
                }
            }
        }

        return $this->selectedModifiersSpellParameters;
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
}