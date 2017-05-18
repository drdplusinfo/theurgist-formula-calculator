<?php
namespace DrdPlus\Theurgist\Configurator;

/** @var IndexController $controller */
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Spells\FormulasTable;
use DrdPlus\Theurgist\Spells\ModifiersTable;
use DrdPlus\Theurgist\Spells\SpellTraitsTable;

$selectedModifiersTree = $controller->getSelectedModifiersTree();
$possibleModifierCombinations = $controller->getPossibleModifierCombinations();
$selectedModifiersSpellTraitValues = $controller->getSelectedModifiersSpellTraitValues();

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
/** @var FormulaCode $selectedFormulaCode */
/** @var SpellTraitsTable $spellTraitsTable */
?>
<div class="modifier panel">
    <?php
    // modifiers of modifiers (their chain)
    /** @noinspection OnlyWritesOnParameterInspection */
    $showModifiers = function (string $parentModifierValue, int $treeLevel)
    use (&$showModifiers, $selectedModifiersTree, $possibleModifierCombinations, $controller, $isModifierSelected, $selectedModifiersSpellTraitValues, $modifiersTable, $spellTraitsTable) {
        if (!array_key_exists($parentModifierValue, $possibleModifierCombinations)) {
            return;
        }
        /** @var array|string[] $selectedRelatedModifiers */
        $modifiersIndex = "{$treeLevel}-{$parentModifierValue}";
        /** @var array|\DrdPlus\Theurgist\Codes\ModifierCode[][] $possibleModifierCombinations */
        foreach ($possibleModifierCombinations[$parentModifierValue] as $possibleModifierValue => $possibleModifier) {
            $modifierSelected = $isModifierSelected($possibleModifierValue, $selectedModifiersTree, $treeLevel);
            ?>
            <div class="modifier">
                <label>
                    <input name="modifiers[<?= $modifiersIndex ?>][]"
                           type="checkbox"
                           value="<?= $possibleModifierValue ?>"
                           <?php if ($modifierSelected){ ?>checked<?php } ?>>
                    <?= /** @var \DrdPlus\Theurgist\Codes\ModifierCode $possibleModifier */
                    $possibleModifier->translateTo('cs');
                    $modifierDifficultyChange = $modifiersTable->getDifficultyChange($possibleModifier)->getValue() ?>
                    <span><?= ($modifierDifficultyChange >= 0 ? '+' : '') . $modifierDifficultyChange ?></span>
                    <span class="forms" title="Forma">
                                <?php
                                $forms = $controller->getModifierFormNames($possibleModifier, 'cs');
                                if (count($forms) > 0) {
                                    echo '(' . implode(', ', $forms) . ')';
                                } ?>
                            </span>
                </label>
                <?php
                require __DIR__ . '/modifier_spell_traits.php';
                $showModifiers($possibleModifierValue, $treeLevel + 1); /* recursion to build tree */
                ?>
            </div>
        <?php }
    };
    $showModifiers('', 1/* tree level */); ?>
</div>