<?php
$currentFormula = $controller->getCurrentFormula();
$currentFormulaCode = $currentFormula->getFormulaCode();
?>
<div class="row">
  <hr class="col">
</div>
<form id="configurator" action="" method="get">
  <input type="hidden" name="<?= $controller::PREVIOUS_FORMULA ?>"
         value="<?= $currentFormulaCode->getValue() ?>">
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