<?php
namespace DrdPlus\Theurgist\Configurator;

/** @var IndexController $controller */
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Formulas\FormulasTable;
use DrdPlus\Theurgist\Formulas\ModifiersTable;
use DrdPlus\Theurgist\Formulas\SpellTraitsTable;

$selectedModifiersTree = $controller->getSelectedModifiersTree();
$selectedModifiersCombinations = $controller->getSelectedModifiersCombinations();
$selectedModifiersSpellTraits = $controller->getSelectedModifiersSpellTraits();

$isModifierSelected = function (string $modifierValue, array $selectedModifiersTreePart) {
    $selection = $selectedModifiersTreePart[$modifierValue] ?? false;
    if ($selection === false) {
        return false;
    }
    return $selection === $modifierValue /* bag end */|| is_array($selection) /* still traversing on the tree */;
};

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
                   <?php if ($isModifierSelected($modifierValue, $selectedModifiersTree)): ?>checked<?php endif ?>>
            <?= $modifier->translateTo('cs') ?>
            <span class="forms" title="Forma">
                <?php
                $forms = $controller->getModifierFormNames($modifier, 'cs');
                if (count($forms) > 0) {
                    echo '(' . implode(', ', $forms) . ')';
                } ?>
            </span>
        </label>
        <?php
        if ($isModifierSelected($modifierValue, $selectedModifiersTree)) {
            $modifierSpellTraits = $modifiersTable->getSpellTraits($modifier);
            if (count($modifierSpellTraits) > 0) { ?>
                <div>
                    <?php foreach ($modifierSpellTraits as $modifierSpellTrait) {
                        $spellTraitValue = $modifierSpellTrait->getSpellTraitCode()->getValue();
                        ?>
                        <div class="spell-trait">
                            <label>
                                <input type="checkbox" value="1"
                                       name="modifierSpellTraits[<?= $modifierValue ?>][<?= $spellTraitValue ?>]"
                                       <?php if (($selectedModifiersSpellTraits[$modifierValue][$spellTraitValue] ?? false) === $spellTraitValue) : ?>checked<?php endif ?>>
                                <?= $modifierSpellTrait->getSpellTraitCode()->translateTo('cs') ?>
                                <?php $trap = $modifierSpellTrait->getTrap($spellTraitsTable);
                                if ($trap !== null) { ?>
                                    <span class="trap">(<?php echo $trap->getValue();
                                        echo " {$trap->getPropertyCode()->translateTo('cs', 1)} [{$trap->getAdditionByRealms()}]";
                                        ?>)</span>
                                <?php } ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            <?php }
            // modifiers of modifiers (their chain)
            $showModifiers = function (string $currentModifierValue, array $selectedModifiers, array $inputNameParts)
            use (&$showModifiers, $selectedModifiersCombinations, $controller, $isModifierSelected) {
                if ($isModifierSelected($currentModifierValue, $selectedModifiers) // is selected
                    && array_key_exists($currentModifierValue, $selectedModifiersCombinations) // and combination is still possible
                ) {
                    /** @var array|string[] $selectedRelatedModifiers */
                    $selectedRelatedModifiers = $selectedModifiers[$currentModifierValue];
                    $selectedRelatedModifiers = is_array($selectedRelatedModifiers) ? $selectedRelatedModifiers : []; // bag end
                    /** @var array|\DrdPlus\Theurgist\Codes\ModifierCode[][] $selectedModifiersCombinations */
                    foreach ($selectedModifiersCombinations[$currentModifierValue] as $possibleModifierValue => $possibleModifier) {
                        $currentInputNameParts = $inputNameParts;
                        $currentInputNameParts[] = $possibleModifierValue;
                        ?>
                        <div class="modifier">
                            <label>
                                <input name="modifiers<?= /** @noinspection PhpParamsInspection */
                                $controller->createModifierInputIndex($currentInputNameParts) ?>" type="checkbox"
                                       value="1"
                                       <?php if ($isModifierSelected($possibleModifierValue, $selectedRelatedModifiers)): ?>checked<?php endif ?>>
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
            $showModifiers($modifier->getValue(), $selectedModifiersTree, [$modifier->getValue()]);
        } ?>
    </div>
<?php }