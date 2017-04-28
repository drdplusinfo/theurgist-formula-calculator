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

    return $selection === $modifierValue /* bag end */ || is_array($selection); /* still traversing on the tree */
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
            <?php $modifierDifficultyChange = $modifiersTable->getDifficultyChange($modifier)->getValue() ?>
            <span><?= ($modifierDifficultyChange > 0 ? '+' : '') . $modifierDifficultyChange ?></span>
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
                                       name="modifierSpellTraits<?= $controller->createSpellTraitInputIndex([$modifierValue], $spellTraitValue) ?>"
                                       <?php if (($selectedModifiersSpellTraits[$modifierValue][$spellTraitValue] ?? false) === $spellTraitValue) : ?>checked<?php endif ?>>
                                <?= $modifierSpellTrait->getSpellTraitCode()->translateTo('cs') ?>
                                <?php $spellTraitDifficulty = $spellTraitsTable->getDifficultyChange($modifierSpellTrait->getSpellTraitCode());
                                echo ($spellTraitDifficulty->getValue() >= 0 ? '+' : '') . $spellTraitDifficulty->getValue();
                                $trap = $modifierSpellTrait->getTrap($spellTraitsTable);
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
            $showModifiers = function (string $currentModifierValue, array $selectedModifiers, array $inputNameParts, array $selectedSpellTraits, bool $showUnchecked = false)
            use (&$showModifiers, $selectedModifiersCombinations, $controller, $isModifierSelected, $modifiersTable, $spellTraitsTable) {
                if ($showUnchecked
                    || (array_key_exists($currentModifierValue, $selectedModifiersCombinations) // combination is possible
                        && $isModifierSelected($currentModifierValue, $selectedModifiers) // and is selected
                    )
                ) {
                    /** @var array|string[] $selectedRelatedModifiers */
                    $selectedRelatedModifiers = $selectedModifiers[$currentModifierValue];
                    $selectedRelatedModifiers = is_array($selectedRelatedModifiers) ? $selectedRelatedModifiers : []; // bag end
                    $selectedRelatedSpellTraits = $selectedSpellTraits[$currentModifierValue] ?? [];
                    $selectedRelatedSpellTraits = is_array($selectedRelatedSpellTraits) ? $selectedRelatedSpellTraits : []; // bag end
                    /** @var array|\DrdPlus\Theurgist\Codes\ModifierCode[][] $selectedModifiersCombinations */
                    foreach ($selectedModifiersCombinations[$currentModifierValue] as $possibleModifierValue => $possibleModifier) {
                        $currentInputNameParts = $inputNameParts;
                        $currentInputNameParts[] = $possibleModifierValue;
                        ?>
                        <div class="modifier">
                            <label>
                                <input name="modifiers<?= $controller->createModifierInputIndex($currentInputNameParts) ?>"
                                       type="checkbox"
                                       value="1"
                                       <?php if ($isModifierSelected($possibleModifierValue, $selectedRelatedModifiers)): ?>checked<?php endif ?>>
                                <?= /** @var \DrdPlus\Theurgist\Codes\ModifierCode $possibleModifier */
                                $possibleModifier->translateTo('cs');
                                $modifierDifficultyChange = $modifiersTable->getDifficultyChange($possibleModifier)->getValue() ?>
                                <span><?= ($modifierDifficultyChange > 0 ? '+' : '') . $modifierDifficultyChange ?></span>
                                <span class="forms" title="Forma">
                                <?php
                                $forms = $controller->getModifierFormNames($possibleModifier, 'cs');
                                if (count($forms) > 0) {
                                    echo '(' . implode(', ', $forms) . ')';
                                } ?>
                            </span>
                            </label>
                            <?php if ($isModifierSelected($possibleModifierValue, $selectedRelatedModifiers)) {
                                $modifierSpellTraits = $modifiersTable->getSpellTraits($possibleModifier);
                                $selectedModifierSpellTraits = $selectedRelatedSpellTraits[$possibleModifierValue] ?? [];
                                if (count($modifierSpellTraits) > 0) { ?>
                                    <div>
                                        <?php foreach ($modifierSpellTraits as $modifierSpellTrait) {
                                            $spellTraitValue = $modifierSpellTrait->getSpellTraitCode()->getValue();
                                            ?>
                                            <div class="spell-trait">
                                                <label>
                                                    <input type="checkbox" value="1"
                                                           name="modifierSpellTraits<?= $controller->createSpellTraitInputIndex($currentInputNameParts, $spellTraitValue) ?>"
                                                           <?php if (array_key_exists($spellTraitValue, $selectedModifierSpellTraits)) : ?>checked<?php endif ?>>
                                                    <?= $modifierSpellTrait->getSpellTraitCode()->translateTo('cs') ?>
                                                    <?php $spellTraitDifficulty = $spellTraitsTable->getDifficultyChange($modifierSpellTrait->getSpellTraitCode());
                                                    echo ($spellTraitDifficulty->getValue() >= 0 ? '+' : '') . $spellTraitDifficulty->getValue();
                                                    $trap = $modifierSpellTrait->getTrap($spellTraitsTable);
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
                            }
                            $showModifiers($possibleModifierValue, $selectedRelatedModifiers, $currentInputNameParts, $selectedRelatedSpellTraits); /* recursion to build tree */ ?>
                        </div>
                    <?php }
                }
            };
            $showModifiers($modifier->getValue(), $selectedModifiersTree, [$modifier->getValue()], $selectedModifiersSpellTraits, false /* do not show not-checked */);
        } ?>
    </div>
<?php }