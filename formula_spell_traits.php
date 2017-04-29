<?php
$formulaSpellTraits = $formulasTable->getSpellTraits($selectedFormula);
$selectedFormulaSpellTraitIndexes = $controller->getSelectedFormulaSpellTraitIndexes();
if (count($formulaSpellTraits) > 0) { ?>
    <div class="block">
        <div class="panel">
            <span class="panel">Rysy:</span>
            <?php foreach ($formulaSpellTraits as $formulaSpellTrait) { ?>
                <div class="spell-trait panel">
                    <label>
                        <input type="checkbox" name="formulaSpellTraits[<?= $formulaSpellTrait->getSpellTraitCode() ?>]"
                               value="1"
                               <?php if (in_array($formulaSpellTrait->getSpellTraitCode()->getValue(), $selectedFormulaSpellTraitIndexes, true)) : ?>checked<?php endif ?>>
                        <?= $formulaSpellTrait->getSpellTraitCode()->translateTo('cs') ?>
                        <?php
                        $spellTraitDifficulty = $spellTraitsTable->getDifficultyChange($formulaSpellTrait->getSpellTraitCode());
                        echo ($spellTraitDifficulty->getValue() >= 0 ? '+' : '') . $spellTraitDifficulty->getValue() ?>
                        <?php $trap = $formulaSpellTrait->getTrap($spellTraitsTable);
                        if ($trap !== null) { ?>
                            <span class="trap">(<?php echo $trap->getValue();
                                echo " {$trap->getPropertyCode()->translateTo('cs', 1)} [{$trap->getAdditionByRealms()}]"; ?>
                                )</span>
                        <?php } ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>