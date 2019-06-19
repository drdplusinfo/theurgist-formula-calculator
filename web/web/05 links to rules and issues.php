<?php
use Granam\String\StringTools;

?>

<div class="row">
  <hr class="col">
</div>
<div class="row">
  <div class="col">
      <?php
      /** @var \DrdPlus\Calculators\Theurgist\FormulaWebPartsContainer $webPartsContainer */
      $formulaCzechName = $webPartsContainer->getCurrentFormula()->getFormulaCode()->translateTo('cs');
      $formula_hash = StringTools::toSnakeCaseId($formulaCzechName);
      ?>
    <div class="name">
      <a href="https://theurg.drdplus.info/?trial=1#<?= $formula_hash ?>"><?= $formulaCzechName ?></a>
    </div>
    <div>
      <a href="https://theurg.drdplus.info/?trial=1#tabulka_formuli">Tabulka formulí</a>
    </div>
  </div>
  <div class="col">
      <?= /** @var \DrdPlus\Calculators\Theurgist\FormulaWebPartsContainer $webPartsContainer */
      $webPartsContainer->getCalculatorDebugContactsBody(); ?>
  </div>
</div>