<?php
$currentFormula = $controller->getCurrentFormula();
$currentFormulaCode = $currentFormula->getFormulaCode();
?>
<div class="row">
  <hr class="col">
</div>
<div class="row">
  <form id="configurator" action="" method="get">
    <input type="hidden" name="<?= $controller::PREVIOUS_FORMULA ?>"
           value="<?= $currentFormulaCode->getValue() ?>">
      <?php require __DIR__ . '/../parts/formula/formula.php'; ?>
    <hr class="clear">
      <?php require __DIR__ . '/../parts/modifiers/modifiers.php' ?>
    <hr class="clear">
    <button type="submit">Vybrat</button>
  </form>
</div>
