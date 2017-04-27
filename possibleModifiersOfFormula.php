<?php
namespace DrdPlus\Theurgist\Configurator;

/** @var IndexController $controller */
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Formulas\FormulasTable;
use DrdPlus\Theurgist\Formulas\ModifiersTable;

$selectedModifierIndexes = $controller->getSelectedModifierIndexes();
$modifierCombinations = $controller->getModifierCombinations();

/** @var FormulasTable $formulasTable */
/** @var ModifiersTable $modifiersTable */
/** @var FormulaCode $selectedFormula */
foreach ($formulasTable->getModifiers($selectedFormula) as $modifier) { ?>
    <div class="modifier panel">
        <label>
            <input name="modifiers[<?= $modifier->getValue() ?>]" type="checkbox"
                   value="<?= $modifier ?>"
                   <?php if (array_key_exists($modifier->getValue(), $selectedModifierIndexes)): ?>checked<?php endif ?>>
            <?= $modifier->translateTo('cs') ?>
            <span class="forms">
                <?php
                $forms = $controller->getModifierFormNames($modifier, 'cs');
                if (count($forms) > 0) {
                    echo '(Forma: ' . implode(', ', $forms) . ')';
                } ?>
            </span>
        </label>
        <?php
        $showModifiers = function (string $currentModifierValue, array $selectedModifiers, array $inputNameParts)
        use (&$showModifiers, $modifierCombinations, $controller) {
            if (array_key_exists($currentModifierValue, $selectedModifiers) && array_key_exists($currentModifierValue, $modifierCombinations)) {
                /** @var array|string[] $selectedRelatedModifiers */
                $selectedRelatedModifiers = $selectedModifiers[$currentModifierValue];
                /** @var array|\DrdPlus\Theurgist\Codes\ModifierCode[][] $modifierCombinations */
                foreach ($modifierCombinations[$currentModifierValue] as $possibleModifierValue => $possibleModifier) {
                    $currentInputNameParts = $inputNameParts;
                    $currentInputNameParts[] = $possibleModifierValue;
                    ?>
                    <div class="modifier">
                        <label>
                            <input name="modifiers<?= /** @noinspection PhpParamsInspection */
                            $controller->createModifierInputIndex($currentInputNameParts) ?>"
                                   type="checkbox" value="<?= $possibleModifierValue ?>"
                                   <?php if (array_key_exists($possibleModifierValue, $selectedRelatedModifiers)): ?>checked<?php endif ?>>
                            <?= /** @var \DrdPlus\Theurgist\Codes\ModifierCode $possibleModifier */
                            $possibleModifier->translateTo('cs') ?>
                            <span class="forms">
                                <?php
                                $forms = $controller->getModifierFormNames($possibleModifier, 'cs');
                                if (count($forms) > 0) {
                                    echo '(Forma: ' . implode(', ', $forms) . ')';
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