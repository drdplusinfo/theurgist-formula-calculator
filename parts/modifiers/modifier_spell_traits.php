<?php
namespace DrdPlus\TheurgistCalculator\Formulas;

/** @var ModifierCode $possibleModifier */
/** @var int $treeLevel */
/** @var ModifiersTable $modifiersTable */
/** @var SpellTraitsTable $spellTraitsTable */
/** @var array $selectedModifiersSpellTraitValues */
/** @var FormulasController $controller */
/** @var bool $modifierIsSelected */

use DrdPlus\Theurgist\Codes\ModifierCode;
use DrdPlus\Theurgist\Spells\ModifiersTable;
use DrdPlus\Theurgist\Spells\SpellParameters\Trap;
use DrdPlus\Theurgist\Spells\SpellTraitsTable;

$modifierSpellTraitCodes = $modifiersTable->getSpellTraitCodes($possibleModifier);
if (\count($modifierSpellTraitCodes) > 0) { ?>
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
                   name="<?= FormulasController::MODIFIER_SPELL_TRAITS ?>[<?= $spellTraitsInputIndex ?>][]"
                   <?php
                   if (\in_array($spellTraitCodeValue, $selectedModifiersSpellTraitValues[$treeLevel][$possibleModifierValue] ?? [], true)) { ?>checked<?php }
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
                        <select name="<?= $controller::MODIFIER_SPELL_TRAIT_TRAPS ?>[<?= $trapSelectIndex ?>]"
                                <?php if (!$modifierIsSelected) { ?>disabled<?php } ?>>
                            <?php
                            $trapAddition = $trap->getAdditionByDifficulty();
                            $additionStep = $trapAddition->getAdditionStep();
                            $optionTrapValue = $trap->getDefaultValue(); // from the lowest
                            $difficultyChange = $trapAddition->getCurrentDifficultyIncrement();
                            $optionTrapChange = 0;
                            $previousOptionTrapValue = null;
                            $selectedTrapValue = $controller->getCurrentModifiersSpellTraitsTrapValues()[$treeLevel][$possibleModifierValue][$spellTraitCodeValue] ?? false;
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