<?php
namespace DrdPlus\Theurgist\Formulas;

use DrdPlus\Calculators\Theurgist\CurrentFormulaValues;

/** @var \DrdPlus\Calculators\Theurgist\FormulaWebPartsContainer $webPartsContainer */

$tables = $webPartsContainer->getTables();
$currentFormulaValues = $webPartsContainer->getCurrentFormulaValues();
$selectedModifiersTree = $currentFormulaValues->getCurrentModifiersTree();
$possibleModifierCombinations = $currentFormulaValues->getPossibleModifierCombinations();
$selectedModifiersSpellTraitValues = $currentFormulaValues->getCurrentModifiersSpellTraitValues();
$modifiersTable = $tables->getModifiersTable();

?>
<div id="modifiers">
  <div class="row">
    <div class="col"><strong>Modifikátory</strong>:</div>
  </div>
  <div class="row">
    <div class="col">
        <?php
        // modifiers of modifiers (their chain)
        $showModifiers = function (string $parentModifierValue, int $treeLevel)
        use (&$showModifiers, $selectedModifiersTree, $possibleModifierCombinations, $currentFormulaValues, $selectedModifiersSpellTraitValues, $modifiersTable, $tables) {
            if (!\array_key_exists($parentModifierValue, $possibleModifierCombinations)) {
                return;
            }
            /** @var array|string[] $selectedRelatedModifiers */
            $modifiersIndex = "{$treeLevel}-{$parentModifierValue}";
            /**
             * @var array|\DrdPlus\Codes\Theurgist\ModifierCode[][] $possibleModifierCombinations
             * @var \DrdPlus\Codes\Theurgist\ModifierCode $possibleModifier
             */
            foreach ($possibleModifierCombinations[$parentModifierValue] as $possibleModifierValue => $possibleModifier) {
                $modifierIsSelected = $currentFormulaValues->isModifierSelected($possibleModifierValue, $selectedModifiersTree, $treeLevel);
                ?>
              <div class="modifier panel">
                <div>
                  <label>
                    <input name="<?= CurrentFormulaValues::MODIFIERS ?>[<?= $modifiersIndex ?>][]"
                           type="checkbox"
                           value="<?= $possibleModifierValue ?>"
                           <?php if ($modifierIsSelected){ ?>checked<?php } ?>>
                      <?= $possibleModifier->translateTo('cs'); ?>
                      <?php $modifierDifficultyChange = $modifiersTable->getDifficultyChange($possibleModifier)->getValue() ?>
                    <span>[<?= ($modifierDifficultyChange >= 0 ? '+' : '') . $modifierDifficultyChange ?>]</span>
                    <span class="forms" title="Forma">
                      <?php
                      $forms = $currentFormulaValues->getModifierFormNames($possibleModifier, 'cs');
                      if (\count($forms) > 0) {
                          echo '(' . \implode(', ', $forms) . ')';
                      } ?>
                  </span>
                  </label>
                </div>
                  <?php
                  require __DIR__ . '/modifier_parameters.php';
                  require __DIR__ . '/modifier_spell_traits.php';
                  if ($modifierIsSelected) {
                      /* recursion to build tree */
                      $showModifiers($possibleModifierValue, $treeLevel + 1);
                  }
                  ?>
              </div>
            <?php }
        };
        $showModifiers('', 1/* tree level */); ?>
    </div>
  </div>
</div>
