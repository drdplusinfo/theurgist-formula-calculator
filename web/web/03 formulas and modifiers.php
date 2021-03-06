<?php
use DrdPlus\Calculators\Theurgist\CurrentFormulaValues;

/** @var \DrdPlus\Calculators\Theurgist\FormulaWebPartsContainer $webPartsContainer */ ?>
<div class="row">
  <hr class="col">
</div>
<form id="configurator" action="" method="get">
  <input type="hidden" name="<?= CurrentFormulaValues::PREVIOUS_FORMULA ?>"
         value="<?= $webPartsContainer->getCurrentFormulaCode()->getValue() ?>">
    <?php require __DIR__ . '/../parts/formula/formula.php'; ?>
  <div class="row">
    <hr class="col">
  </div>
    <?php require __DIR__ . '/../parts/modifiers/modifiers.php' ?>
  <div class="row">
    <hr class="col">
  </div>
  <button type="submit">Vybrat</button>
</form>