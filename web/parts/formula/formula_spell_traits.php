<?php
/** @var \DrdPlus\Tables\Tables $tables */
/** @var \DrdPlus\Codes\Theurgist\FormulaCode $currentFormulaCode */
/** @var \DrdPlus\Calculators\Theurgist\CurrentFormulaValues $currentFormulaValues */

$formulaSpellTraitCodes = $tables->getFormulasTable()->getSpellTraitCodes($currentFormulaCode);
if (\count($formulaSpellTraitCodes) > 0) {
    $selectedFormulaSpellTraitValues = $currentFormulaValues->getCurrentFormulaSpellTraitValues();
    $spellTraitsTable = $tables->getSpellTraitsTable();
    ?>
  <div class="row">
    <div class="col">
      <strong>Rysy</strong>:
        <?php foreach ($formulaSpellTraitCodes as $formulaSpellTraitCode) { ?>
          <div class="spell-trait">
            <label>
              <input type="checkbox" name="formula_spell_traits[]"
                     value="<?= $formulaSpellTraitCode ?>"
                     <?php if (\in_array($formulaSpellTraitCode->getValue(), $selectedFormulaSpellTraitValues, true)) : ?>checked<?php endif ?>>
                <?= $formulaSpellTraitCode->translateTo('cs') ?>
                <?php
                $spellTraitDifficulty = $spellTraitsTable->getDifficultyChange($formulaSpellTraitCode);
                echo '[' . ($spellTraitDifficulty->getValue() >= 0 ? '+' : '') . $spellTraitDifficulty->getValue() . ']' ?>
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