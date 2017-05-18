<?php
namespace DrdPlus\Theurgist\Configurator;

/** @var ModifierCode $possibleModifier */
/** @var int $treeLevel */
/** @var ModifiersTable $modifiersTable */
/** @var SpellTraitsTable $spellTraitsTable */
/** @var array $selectedModifiersSpellTraitValues */
/** @var IndexController $controller */

use DrdPlus\Theurgist\Codes\ModifierCode;
use DrdPlus\Theurgist\Spells\ModifiersTable;
use DrdPlus\Theurgist\Spells\SpellTraitsTable;

$modifierSpellTraitCodes = $modifiersTable->getSpellTraitCodes($possibleModifier);
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
                           name="modifierSpellTraits[<?= $spellTraitsInputIndex ?>][]"
                           <?php if (in_array($spellTraitCodeValue, $selectedModifiersSpellTraitValues[$treeLevel][$possibleModifierValue] ?? [], true)) : ?>checked<?php endif ?>>
                    <?= $modifierSpellTraitCode->translateTo('cs') ?>
                    <?php $spellTraitDifficulty = $spellTraitsTable->getDifficultyChange($modifierSpellTraitCode);
                    echo ($spellTraitDifficulty->getValue() >= 0 ? '+' : '') . $spellTraitDifficulty->getValue();
                    $trap = $spellTraitsTable->getTrap($modifierSpellTraitCode);
                    if ($trap !== null) {
                        $trapSelectIndex = "$spellTraitsInputIndex-{$spellTraitCodeValue}";
                        ?>
                        <span class="trap">
                        <select name="modifierSpellTraitTraps[<?= $trapSelectIndex ?>]">
                            <?php
                            $trapAddition = $trap->getAdditionByDifficulty();
                            $additionStep = $trapAddition->getAdditionStep();
                            $difficultyOfAdditionStep = $trapAddition->getDifficultyOfAdditionStep();
                            $optionTrapValue = $trap->getDefaultValue(); // from the lowest
                            $previousOptionTrapValue = null;
                            $selectedTrapValue = $controller->getSelectedModifiersSpellTraitsTrapValues()[$treeLevel][$possibleModifierValue][$spellTraitCodeValue] ?? false;
                            do {
                                if ($previousOptionTrapValue === null || $previousOptionTrapValue < $optionTrapValue) { ?>
                                    <option value="<?= $optionTrapValue ?>"
                                            <?php if ($selectedTrapValue !== false && $selectedTrapValue === $optionTrapValue){ ?>selected<?php } ?>>
                                        <?= ($optionTrapValue >= 0 ? '+' : '')
                                        . "{$optionTrapValue}"; ?>
                                    </option>
                                <?php }
                                $previousOptionTrapValue = $optionTrapValue;
                            } while ($additionStep > 0 /* at least once even on no addition possible */
                            && $optionTrapValue++ / $additionStep * $difficultyOfAdditionStep < 21 /* difficulty change */) ?>
                        </select>
                        <?= "{$trap->getPropertyCode()->translateTo('cs', 1)} [{$trap->getAdditionByDifficulty()}]" ?>
                        </span>
                    <?php } ?>
                </label>
            </div>
        <?php } ?>
    </div>
<?php }