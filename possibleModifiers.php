<?php
namespace DrdPlus\Theurgist\Configurator;

/** @var IndexController $controller */
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Formulas\FormulasTable;
use DrdPlus\Theurgist\Formulas\ModifiersTable;
use DrdPlus\Theurgist\Formulas\SpellTraitsTable;

$selectedModifierIndexes = $controller->getSelectedModifierIndexes();
$selectedModifierCombinations = $controller->getSelectedModifierCombinations();

/** @var FormulasTable $formulasTable */
/** @var ModifiersTable $modifiersTable */
/** @var FormulaCode $selectedFormula */
/** @var SpellTraitsTable $spellTraitsTable */
foreach ($formulasTable->getModifiers($selectedFormula) as $modifier) { ?>
    <div class="modifier panel">
        <label>
            <input name="modifiers[<?= $modifier->getValue() ?>]" type="checkbox" value="1"
                   <?php if (array_key_exists($modifier->getValue(), $selectedModifierIndexes)): ?>checked<?php endif ?>>
            <?= $modifier->translateTo('cs') ?>
            <span class="forms" title="Forma">
                <?php
                $forms = $controller->getModifierFormNames($modifier, 'cs');
                if (count($forms) > 0) {
                    echo '(' . implode(', ', $forms) . ')';
                } ?>
            </span>
        </label>
        <?php $modifierSpellTraits = $modifiersTable->getSpellTraits($modifier);
        $selectedFormulaSpellTraitIndexes = $controller->getSelectedFormulaSpellTraitIndexes();
        if (count($modifierSpellTraits) > 0) { ?>
            <div style="background-color: #1b6d85">
                <?php foreach ($modifierSpellTraits as $modifierSpellTrait) { ?>
                    <div class="spell-trait">
                        <label>
                            <input type="checkbox" value="1"
                                   name="modifierSpellTraits[<?= $modifier->getValue() ?>][<?= $modifierSpellTrait->getSpellTraitCode() ?>]"
                                   <?php if (in_array($modifierSpellTrait->getSpellTraitCode()->getValue(), $selectedFormulaSpellTraitIndexes, true)) : ?>checked<?php endif ?>>
                            <?= $modifierSpellTrait->getSpellTraitCode()->translateTo('cs') ?>
                            <?php $modifierSpellTrap = $modifierSpellTrait->getTrap($spellTraitsTable);
                            if ($modifierSpellTrap !== null) {
                                echo "({$modifierSpellTrap})";
                            } ?>
                        </label>
                    </div>
                <?php } ?>
            </div>
        <?php }
        // modifiers of modifiers (their chain)
        $showModifiers = function (string $currentModifierValue, array $selectedModifiers, array $inputNameParts)
        use (&$showModifiers, $selectedModifierCombinations, $controller) {
            if (array_key_exists($currentModifierValue, $selectedModifiers) && array_key_exists($currentModifierValue, $selectedModifierCombinations)) {
                /** @var array|string[] $selectedRelatedModifiers */
                $selectedRelatedModifiers = $selectedModifiers[$currentModifierValue];
                /** @var array|\DrdPlus\Theurgist\Codes\ModifierCode[][] $selectedModifierCombinations */
                foreach ($selectedModifierCombinations[$currentModifierValue] as $possibleModifierValue => $possibleModifier) {
                    $currentInputNameParts = $inputNameParts;
                    $currentInputNameParts[] = $possibleModifierValue;
                    ?>
                    <div class="modifier">
                        <label>
                            <input name="modifiers<?= /** @noinspection PhpParamsInspection */
                            $controller->createModifierInputIndex($currentInputNameParts) ?>" type="checkbox" value="1"
                                   <?php if (array_key_exists($possibleModifierValue, $selectedRelatedModifiers)): ?>checked<?php endif ?>>
                            <?= /** @var \DrdPlus\Theurgist\Codes\ModifierCode $possibleModifier */
                            $possibleModifier->translateTo('cs') ?>
                            <span class="forms" title="Forma">
                                <?php
                                $forms = $controller->getModifierFormNames($possibleModifier, 'cs');
                                if (count($forms) > 0) {
                                    echo '(' . implode(', ', $forms) . ')';
                                } ?>
                            </span>
                        </label>
                        <?php $showModifiers($possibleModifierValue, $selectedRelatedModifiers, $currentInputNameParts) ?>
                    </div>
                <?php }
            }
        };
        $showModifiers($modifier->getValue(), $selectedModifierIndexes, [$modifier->getValue()]); ?>
    </div>
<?php }