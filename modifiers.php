<?php
namespace DrdPlus\Theurgist\Configurator;

/** @var IndexController $controller */
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Formulas\FormulasTable;
use DrdPlus\Theurgist\Formulas\ModifiersTable;
use DrdPlus\Theurgist\Formulas\SpellTraitsTable;

$selectedModifierIndexes = $controller->getSelectedModifierIndexes();
$selectedModifierCombinations = $controller->getSelectedModifierCombinations();
$selectedModifiersSpellTraitIndexes = $controller->getSelectedModifiersSpellTraitIndexes();

/** @var FormulasTable $formulasTable */
/** @var ModifiersTable $modifiersTable */
/** @var FormulaCode $selectedFormula */
/** @var SpellTraitsTable $spellTraitsTable */
foreach ($formulasTable->getModifiers($selectedFormula) as $modifier) {
    $modifierValue = $modifier->getValue();
    ?>
    <div class="modifier panel">
        <label>
            <input name="modifiers[<?= $modifierValue ?>]" type="checkbox" value="1"
                   <?php if (array_key_exists($modifierValue, $selectedModifierIndexes)): ?>checked<?php endif ?>>
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
        if (count($modifierSpellTraits) > 0) { ?>
            <div>
                <?php foreach ($modifierSpellTraits as $modifierSpellTrait) {
                    $spellTraitValue = $modifierSpellTrait->getSpellTraitCode()->getValue();
                    ?>
                    <div class="spell-trait">
                        <label>
                            <input type="checkbox" value="1"
                                   name="modifierSpellTraits[<?= $modifierValue ?>][<?= $spellTraitValue ?>]"
                                   <?php if (($selectedModifiersSpellTraitIndexes[$modifierValue][$spellTraitValue] ?? false) === $spellTraitValue) : ?>checked<?php endif ?>>
                            <?= $modifierSpellTrait->getSpellTraitCode()->translateTo('cs') ?>
                            <?php $trap = $modifierSpellTrait->getTrap($spellTraitsTable);
                            if ($trap !== null) { ?>
                                <span class="trap">(<?php echo $trap->getValue();
                                    echo " {$trap->getPropertyCode()} [{$trap->getAdditionByRealms()}]";

                                    ?>)</span>
                            <?php } ?>
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