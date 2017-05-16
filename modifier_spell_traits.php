<?php
/** @var \DrdPlus\Theurgist\Codes\ModifierCode $possibleModifier */
/** @var int $treeLevel */
/** @var string $possibleModifierValue */
$modifierSpellTraitCodes = $modifiersTable->getSpellTraitCodes($possibleModifier);
if (count($modifierSpellTraitCodes) > 0) { ?>
    <div>
        <?php
        $spellTraitsInputIndex = "{$treeLevel}-{$possibleModifierValue}";
        foreach ($modifierSpellTraitCodes as $modifierSpellTraitCode) {
            $spellTraitCodeValue = $modifierSpellTraitCode->getValue();
            ?>
            <div class="spell-trait">
                <label>
                    <input type="checkbox" value="<?= $spellTraitCodeValue ?>"
                           name="modifierSpellTraits[<?= $spellTraitsInputIndex ?>][]"
                           <?php if (in_array($spellTraitCodeValue, $selectedModifiersSpellTraits[$treeLevel][$possibleModifierValue] ?? [], true)) : ?>checked<?php endif ?>>
                    <?= $modifierSpellTraitCode->translateTo('cs') ?>
                    <?php $spellTraitDifficulty = $spellTraitsTable->getDifficultyChange($modifierSpellTraitCode);
                    echo ($spellTraitDifficulty->getValue() >= 0 ? '+' : '') . $spellTraitDifficulty->getValue();
                    $trap = $spellTraitsTable->getTrap($modifierSpellTraitCode);
                    if ($trap !== null) { ?>
                        <span class="trap">(<?php echo $trap->getValue();
                            echo " {$trap->getPropertyCode()->translateTo('cs', 1)} [{$trap->getAdditionByDifficulty()}]";
                            ?>)</span>
                    <?php } ?>
                </label>
            </div>
        <?php } ?>
    </div>
<?php }