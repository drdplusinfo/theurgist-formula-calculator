<?php
namespace DrdPlus\TheurgistCalculator\Formulas;

use DrdPlus\Theurgist\Codes\ModifierCode;

/** @var FormulasController $controller */
$selectedModifiersTree = $controller->getCurrentModifiersTree();
$possibleModifierCombinations = $controller->getPossibleModifierCombinations();
$selectedModifiersSpellTraitValues = $controller->getCurrentModifiersSpellTraitValues();
$modifiersTable = $controller->getModifiersTable();
$spellTraitsTable = $controller->getSpellTraitsTable();

?>
<div id="modifiers" class="block">
  <div><strong>Modifikátory</strong>:</div>
  <div class="panel">
      <?php
      // modifiers of modifiers (their chain)
      /** @noinspection OnlyWritesOnParameterInspection */
      $showModifiers = function (string $parentModifierValue, int $treeLevel)
      use (&$showModifiers, $selectedModifiersTree, $possibleModifierCombinations, $controller, $selectedModifiersSpellTraitValues, $modifiersTable, $spellTraitsTable) {
          if (!\array_key_exists($parentModifierValue, $possibleModifierCombinations)) {
              return;
          }
          /** @var array|string[] $selectedRelatedModifiers */
          $modifiersIndex = "{$treeLevel}-{$parentModifierValue}";
          /**
           * @var array|ModifierCode[][] $possibleModifierCombinations
           * @var ModifierCode $possibleModifier
           */
          foreach ($possibleModifierCombinations[$parentModifierValue] as $possibleModifierValue => $possibleModifier) {
              $modifierIsSelected = $controller->isModifierSelected($possibleModifierValue, $selectedModifiersTree, $treeLevel);
              ?>
            <div class="modifier panel">
              <div>
                <label>
                  <input name="<?= $controller::MODIFIERS ?>[<?= $modifiersIndex ?>][]"
                         type="checkbox"
                         value="<?= $possibleModifierValue ?>"
                         <?php if ($modifierIsSelected){ ?>checked<?php } ?>>
                    <?= $possibleModifier->translateTo('cs'); ?>
                    <?php $modifierDifficultyChange = $modifiersTable->getDifficultyChange($possibleModifier)->getValue() ?>
                  <span>[<?= ($modifierDifficultyChange >= 0 ? '+' : '') . $modifierDifficultyChange ?>
                    ]</span>
                  <span class="forms" title="Forma">
                      <?php
                      $forms = $controller->getModifierFormNames($possibleModifier, 'cs');
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
