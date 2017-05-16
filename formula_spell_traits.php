<?php
$formulaSpellTraitCodes = $formulasTable->getSpellTraitCodes($selectedFormulaCode);
$selectedFormulaSpellTraits = $controller->getSelectedFormulaSpellTraitValues();
if (count($formulaSpellTraitCodes) > 0) { ?>
    <div class="block">
        <div class="panel">
            <span class="panel">Rysy:</span>
            <?php foreach ($formulaSpellTraitCodes as $formulaSpellTraitCode) { ?>
                <div class="spell-trait panel">
                    <label>
                        <input type="checkbox" name="formulaSpellTraits[]"
                               value="<?= $formulaSpellTraitCode ?>"
                               <?php if (in_array($formulaSpellTraitCode->getValue(), $selectedFormulaSpellTraits, true)) : ?>checked<?php endif ?>>
                        <?= $formulaSpellTraitCode->translateTo('cs') ?>
                        <?php
                        $spellTraitDifficulty = $spellTraitsTable->getDifficultyChange($formulaSpellTraitCode);
                        echo ($spellTraitDifficulty->getValue() >= 0 ? '+' : '') . $spellTraitDifficulty->getValue() ?>
                        <?php $trap = $spellTraitsTable->getTrap($formulaSpellTraitCode);
                        if ($trap !== null) { ?>
                            <span class="trap">(<?php echo $trap->getValue();
                                echo " {$trap->getPropertyCode()->translateTo('cs', 1)} [{$trap->getAdditionByDifficulty()}]"; ?>
                                )</span>
                        <?php } ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>