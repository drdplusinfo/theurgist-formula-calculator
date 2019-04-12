<?php
namespace DrdPlus\Theurgist\Formulas;

use DrdPlus\Calculators\Theurgist\CurrentFormulaValues;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Trap;

/** @var ModifierCode $possibleModifier */
/** @var int $treeLevel */
/** @var array $selectedModifiersSpellTraitValues */
/** @var bool $modifierIsSelected */
/** @var Tables $tables */
/** @var CurrentFormulaValues $currentFormulaValues */

$modifierSpellTraitCodes = $tables->getModifiersTable()->getSpellTraits($possibleModifier);
$spellTraitsTable = $tables->getSpellTraitsTable();
if (count($modifierSpellTraitCodes) > 0) { ?>
  <div>
      <?php
      $possibleModifierValue = $possibleModifier->getValue();
      $spellTraitsInputIndex = "{$treeLevel}-{$possibleModifierValue}";
      foreach ($modifierSpellTraitCodes as $modifierSpellTraitCode) {
          $spellTraitCodeValue = $modifierSpellTraitCode->getValue();
          ?>
        <div class="spell-trait">
          <label>
            <input type="checkbox" value="<?= $spellTraitCodeValue ?>"
                   name="modifier_spell_traits[<?= $spellTraitsInputIndex ?>][]"
                   <?php
                   if (in_array($spellTraitCodeValue, $selectedModifiersSpellTraitValues[$treeLevel][$possibleModifierValue] ?? [], true)) { ?>checked<?php }
                   if (!$modifierIsSelected) { ?>disabled<?php } ?>
            >
              <?= $modifierSpellTraitCode->translateTo('cs') ?>
              <?php $spellTraitDifficulty = $spellTraitsTable->getDifficultyChange($modifierSpellTraitCode);
              echo '[' . ($spellTraitDifficulty->getValue() >= 0 ? '+' : '') . $spellTraitDifficulty->getValue() . ']';
              $trap = $spellTraitsTable->getTrap($modifierSpellTraitCode);
              if ($trap !== null) {
                  /** @var Trap $trap */
                  $trapSelectIndex = "$spellTraitsInputIndex-{$spellTraitCodeValue}";
                  ?>
                <span class="trap">
                        <select name="<?= CurrentFormulaValues::MODIFIER_SPELL_TRAIT_TRAPS ?>[<?= $trapSelectIndex ?>]"
                                <?php if (!$modifierIsSelected) { ?>disabled<?php } ?>>
                            <?php
                            $trapAddition = $trap->getAdditionByDifficulty();
                            $additionStep = $trapAddition->getAdditionStep();
                            $optionTrapValue = $trap->getDefaultValue(); // from the lowest
                            $difficultyChange = $trapAddition->getCurrentDifficultyIncrement();
                            $optionTrapChange = 0;
                            $previousOptionTrapValue = null;
                            $selectedTrapValue = $currentFormulaValues->getCurrentModifiersSpellTraitsTrapValues()[$treeLevel][$possibleModifierValue][$spellTraitCodeValue] ?? false;
                            do {
                                if ($previousOptionTrapValue === null || $previousOptionTrapValue < $optionTrapValue) { ?>
                                  <option value="<?= $optionTrapValue ?>"
                                          <?php if ($selectedTrapValue !== false && $selectedTrapValue === $optionTrapValue){
                                          ?>selected<?php
                                  } ?>>
                                        <?= ($optionTrapValue >= 0 ? '+' : '')
                                        . "{$optionTrapValue} [{$difficultyChange}]"; ?>
                                    </option>
                                <?php }
                                $previousOptionTrapValue = $optionTrapValue;
                                $optionTrapChange++;
                                $optionTrapValue++;
                                /** @noinspection PhpUnhandledExceptionInspection */
                                $trap = $trap->getWithAddition($optionTrapChange);
                                $trapAddition = $trap->getAdditionByDifficulty();
                                $difficultyChange = $trapAddition->getCurrentDifficultyIncrement();
                            } while ($additionStep > 0 /* at least once even on no addition possible */ && $difficultyChange < 21) ?>
                        </select>
                    <?= $trap->getPropertyCode()->translateTo('cs', 1) ?>
                        </span>
              <?php } ?>
          </label>
        </div>
      <?php } ?>
  </div>
<?php }