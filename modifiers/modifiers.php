<?php
namespace DrdPlus\Theurgist\Configurator;

/** @var IndexController $controller */
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Codes\ModifierCode;
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

/** @var ModifiersTable $modifiersTable */
/** @var FormulaCode $selectedFormulaCode */
/** @var SpellTraitsTable $spellTraitsTable */
?>
<div class="panel">
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
        /** @var array|ModifierCode[][] $possibleModifierCombinations */
        foreach ($possibleModifierCombinations[$parentModifierValue] as $possibleModifierValue => $possibleModifier) {
            $modifierIsSelected = $isModifierSelected($possibleModifierValue, $selectedModifiersTree, $treeLevel);
            ?>
            <div class="modifier panel">
                <label>
                    <input name="modifiers[<?= $modifiersIndex ?>][]"
                           type="checkbox"
                           value="<?= $possibleModifierValue ?>"
                           <?php if ($modifierIsSelected){ ?>checked<?php } ?>>
                    <?= /** @var ModifierCode $possibleModifier */
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
                require __DIR__ . '/modifier_quality_parameter.php';
                require __DIR__ . '/modifier_radius_parameter.php';
                require __DIR__ . '/modifier_number_of_conditions_parameter.php';
                require __DIR__ . '/modifier_spell_traits.php';
                $showModifiers($possibleModifierValue, $treeLevel + 1); /* recursion to build tree */
                ?>
            </div>
        <?php }
    };
    $showModifiers('', 1/* tree level */); ?>
</div>