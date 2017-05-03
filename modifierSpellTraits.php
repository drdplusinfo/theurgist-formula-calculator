<?php
/** @var \DrdPlus\Theurgist\Codes\ModifierCode $possibleModifier */
/** @var int $treeLevel */
/** @var string $possibleModifierValue */
$modifierSpellTraits = $modifiersTable->getSpellTraits($possibleModifier);
if (count($modifierSpellTraits) > 0) { ?>
    <div>
        <?php
        $spellTraitsInputIndex = "{$treeLevel}-{$possibleModifierValue}";
        foreach ($modifierSpellTraits as $modifierSpellTrait) {
            $spellTraitValue = $modifierSpellTrait->getSpellTraitCode()->getValue();
            ?>
            <div class="spell-trait">
                <label>
                    <input type="checkbox" value="<?= $spellTraitValue ?>"
                           name="modifierSpellTraits[<?= $spellTraitsInputIndex ?>][]"
                           <?php if (in_array($spellTraitValue, $selectedModifiersSpellTraits[$treeLevel][$possibleModifierValue] ?? [], true)) : ?>checked<?php endif ?>>
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