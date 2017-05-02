<?php
namespace DrdPlus\Theurgist\Configurator;

/** @var IndexController $controller */
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Formulas\FormulasTable;
use DrdPlus\Theurgist\Formulas\ModifiersTable;
use DrdPlus\Theurgist\Formulas\SpellTraitsTable;

$selectedModifiersTree = $controller->getSelectedModifiersTree();
$possibleModifierCombinations = $controller->getPossibleModifierCombinations();
$selectedModifiersSpellTraits = $controller->getSelectedModifiersSpellTraits();

$isModifierSelected = function (string $modifierValue, array $selectedModifiers, int $treeLevel) {
    $levelSelection = $selectedModifiers[$treeLevel] ?? false;
    if ($levelSelection === false) {
        return false;
    }
    $selection = $levelSelection[$modifierValue] ?? false;
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
            <input name="modifiers[1-][]" type="checkbox" value="<?= $modifierValue ?>"
                   <?php if ($isModifierSelected($modifierValue, $selectedModifiersTree, 1)): ?>checked<?php endif ?>>
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
        if ($isModifierSelected($modifierValue, $selectedModifiersTree, 1)) {
            $modifierSpellTraits = $modifiersTable->getSpellTraits($modifier);
            if (count($modifierSpellTraits) > 0) { ?>
                <div>
                    <?php foreach ($modifierSpellTraits as $modifierSpellTrait) {
                        $spellTraitValue = $modifierSpellTrait->getSpellTraitCode()->getValue();
                        ?>
                        <div class="spell-trait">
                            <label>
                                <input type="checkbox" value="1"
                                       name="modifierSpellTraits[1-][]"
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
            $showModifiers = function (string $parentModifierValue, int $treeLevel, array $selectedSpellTraits)
            use (&$showModifiers, $selectedModifiersTree, $possibleModifierCombinations, $controller, $isModifierSelected, $modifiersTable, $spellTraitsTable) {
                if (!array_key_exists($parentModifierValue, $possibleModifierCombinations)) {
                    return;
                }
                /** @var array|string[] $selectedRelatedModifiers */
                $selectedRelatedSpellTraits = $selectedSpellTraits[$parentModifierValue] ?? [];
                $selectedRelatedSpellTraits = is_array($selectedRelatedSpellTraits) ? $selectedRelatedSpellTraits : []; // bag end
                $modifiersIndex = "{$treeLevel}-{$parentModifierValue}";
                /** @var array|\DrdPlus\Theurgist\Codes\ModifierCode[][] $possibleModifierCombinations */
                foreach ($possibleModifierCombinations[$parentModifierValue] as $possibleModifierValue => $possibleModifier) { ?>
                    <div class="modifier">
                        <label>
                            <input name="modifiers[<?= $modifiersIndex ?>][]"
                                   type="checkbox"
                                   value="<?= $possibleModifierValue ?>"
                                   <?php if ($isModifierSelected($possibleModifierValue, $selectedModifiersTree, $treeLevel)): ?>checked<?php endif ?>>
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
                        <?php if ($isModifierSelected($possibleModifierValue, $selectedModifiersTree, $treeLevel)) {
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
                                                       name="modifierSpellTraits[<?= $modifiersIndex ?>][]"
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
                            $showModifiers($possibleModifierValue, $treeLevel + 1, $selectedRelatedSpellTraits); /* recursion to build tree */
                        } ?>
                    </div>
                <?php }
            };
            $showModifiers($modifier->getValue(), 2 /* tree level */, $selectedModifiersSpellTraits);
        } ?>
    </div>
<?php }