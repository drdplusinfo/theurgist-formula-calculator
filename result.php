<?php
namespace DrdPlus\Calculator\Theurgist\Formulas;

/** @var Formula $selectedFormula */
/** @var Controller $controller */
use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Codes\FormulaMutableSpellParameterCode;
use DrdPlus\Theurgist\Spells\Formula;
use DrdPlus\Theurgist\Spells\SpellParameters\RealmsAffection;

?>
<div id="result">
  <div class="row">
    <div class="col-md-4">
      Sféra:
      <ol class="realm font-weight-bold" start="<?= $selectedFormula->getRequiredRealm() ?>">
        <li></li>
      </ol>
    </div>
    <div class="col-md-4">
      Náročnost: [<strong><?= $selectedFormula->getCurrentDifficulty()->getValue() ?></strong>]
    </div>
    <div class="col-md-4">
        <?php
        $realmsAffections = $selectedFormula->getCurrentRealmsAffections();
        if (\count($realmsAffections) > 1):?>
          Náklonnosti:
        <?php else: ?>
          Náklonnost:
        <?php endif;
        $inCzech = [];
        /** @var RealmsAffection $realmsAffection */
        foreach ($realmsAffections as $realmsAffection) {
            $inCzech[] = $realmsAffection->getAffectionPeriod()->translateTo('cs') . ' ' . $realmsAffection->getValue();
        } ?>
      <strong><?= \implode(', ', $inCzech); ?></strong>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
      Vyvolání (příprava formule):
        <?php $evocation = $selectedFormula->getCurrentEvocation();
        $evocationTime = $evocation->getEvocationTime(Tables::getIt()->getTimeTable());
        $evocationUnitInCzech = $evocationTime->getUnitCode()->translateTo('cs', $evocationTime->getValue());
        $evocationTimeDescription = ($evocation->getValue() >= 0 ? '+' : '') . $evocation->getValue();
        $evocationTimeDescription .= " ({$evocationTime->getValue()} {$evocationUnitInCzech}";
        if (($evocationTimeInMinutes = $evocationTime->findMinutes()) && $evocationTime->getUnitCode()->getValue() === TimeUnitCode::ROUND) {
            $evocationInMinutesUnitInCzech = $evocationTimeInMinutes->getUnitCode()->translateTo('cs', $evocationTimeInMinutes->getValue());
            $evocationTimeDescription .= '; ' . $evocationTimeInMinutes->getValue() . ' ' . $evocationInMinutesUnitInCzech;
        }
        $evocationTimeDescription .= ')'; ?>
      <strong><?= $evocationTimeDescription ?></strong>
    </div>
    <div class="col-md-4">
      Seslání (vypuštění kouzla):
        <?php $castingRounds = $selectedFormula->getCurrentCastingRounds();
        $castingBonus = $castingRounds->getTime(Tables::getIt()->getTimeTable())->getBonus();
        $casting = $castingBonus->getTime();
        $castingUnitInCzech = $casting->getUnitCode()->translateTo('cs', $casting->getValue()); ?>
      <strong>
          <?= ($castingBonus->getValue() >= 0 ? '+' : '') . "{$castingBonus->getValue()} ({$casting->getValue()} {$castingUnitInCzech})"; ?>
      </strong>
    </div>
    <div class="col-md-4">
      Doba trvání:
        <?php $duration = $selectedFormula->getCurrentDuration();
        $durationTime = $duration->getDurationTime(Tables::getIt()->getTimeTable());
        $durationUnitInCzech = $durationTime->getUnitCode()->translateTo('cs', $durationTime->getValue()); ?>
      <strong>
          <?= ($duration->getValue() >= 0 ? '+' : '') . "{$duration->getValue()} ({$durationTime->getValue()} {$durationUnitInCzech})"; ?>
      </strong>
    </div>
  </div>
  <div class="row">
      <?php $radius = $selectedFormula->getCurrentRadius();
      if ($radius !== null) { ?>
        <div class="col-md">
            <?= FormulaMutableSpellParameterCode::getIt(FormulaMutableSpellParameterCode::RADIUS)->translateTo('cs') ?>:
            <?php $radiusDistance = $radius->getDistance(Tables::getIt()->getDistanceTable());
            $radiusUnitInCzech = $radiusDistance->getUnitCode()->translateTo('cs', $radiusDistance->getValue()); ?>
          <strong><?= ($radius->getValue() >= 0 ? '+' : '') . "{$radius->getValue()} ({$radiusDistance->getValue()}
            {$radiusUnitInCzech})"; ?></strong>
        </div>
      <?php }
      $power = $selectedFormula->getCurrentPower();
      if ($power !== null) { ?>
        <div class="col-md">
          Síla:<strong><?= ($power->getValue() >= 0 ? '+' : '') . $power->getValue(); ?></strong>
        </div>
      <?php }
      $epicenterShiftOfModified = $selectedFormula->getCurrentEpicenterShift();
      if ($epicenterShiftOfModified !== null) {
          $epicenterShiftDistance = $epicenterShiftOfModified->getDistance(Tables::getIt()->getDistanceTable());
          $epicenterShiftUnitInCzech = $epicenterShiftDistance->getUnitCode()->translateTo('cs', $epicenterShiftDistance->getValue());
          ?>
        <div class="col-md">
          Posun transpozicí:
          <strong><?= ($epicenterShiftOfModified->getValue() >= 0 ? '+' : '') .
              "{$epicenterShiftOfModified->getValue()} ({$epicenterShiftDistance->getValue()} {$epicenterShiftUnitInCzech})" ?></strong>
        </div>
      <?php }
      $detailLevel = $selectedFormula->getCurrentDetailLevel();
      if ($detailLevel !== null) {
          ?>
        <div class="col-md">
          Detailnost:
            <?= ($detailLevel->getValue() >= 0 ? '+' : '') . $detailLevel->getValue() ?>
        </div>
      <?php }
      $sizeChange = $selectedFormula->getCurrentSizeChange();
      if ($sizeChange !== null) { ?>
        <div class="col-md">
          Změna velikosti:
            <?= ($sizeChange->getValue() >= 0 ? '+' : '') . $sizeChange->getValue() ?>
        </div>
      <?php }
      $brightness = $selectedFormula->getCurrentBrightness();
      if ($brightness !== null) {
          ?>
        <div class="col-md">
          Jas:
            <?= ($brightness->getValue() >= 0 ? '+' : '') . $brightness->getValue() ?>
        </div>
      <?php }
      $spellSpeed = $selectedFormula->getCurrentSpellSpeed();
      if ($spellSpeed !== null) {
          $speed = $spellSpeed->getSpeed(Tables::getIt()->getSpeedTable());
          $spellSpeedUnitInCzech = $speed->getUnitCode()->translateTo('cs', $speed->getValue());
          ?>
        <div class="col-md">
          Rychlost:
            <?= ($spellSpeed->getValue() >= 0 ? '+' : '') .
            "{$spellSpeed->getValue()} ({$speed->getValue()} {$spellSpeedUnitInCzech})" ?>
        </div>
      <?php }
      $attack = $selectedFormula->getCurrentAttack();
      if ($attack !== null) { ?>
        <div class="col-md">
          Útočnost: <?= ($attack->getValue() >= 0 ? '+' : '') . $attack->getValue(); ?>
        </div>
      <?php } ?>
  </div>
</div>