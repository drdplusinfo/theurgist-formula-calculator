<?php
namespace DrdPlus\TheurgistCalculator\Formulas;

use DrdPlus\Calculators\Theurgist\CurrentFormulaValues;
use DrdPlus\Codes\Theurgist\FormulaMutableSpellParameterCode;
use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\Formula;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;

/** @var Formula $currentFormula*/
/** @var CurrentFormulaValues $currentFormulaValues */

$resultParts = [];
// Roman numerals are created by browser using ordered list with upper Roman list style type
/** @noinspection PhpUnhandledExceptionInspection */
$resultParts[] = <<<HTML
sféra: <ol class="realm font-weight-bold" start="{$currentFormula->getRequiredRealm()}">
        <li></li>
      </ol>
HTML;
/** @noinspection PhpUnhandledExceptionInspection */
$resultParts[] = <<<HTML
náročnost: [<strong>{$currentFormula->getCurrentDifficulty()->getValue()}</strong>]
HTML;
$realmsAffections = $currentFormula->getCurrentRealmsAffections();
$realmsAffectionsInCzech = [];
/** @var RealmsAffection $realmsAffection */
foreach ($realmsAffections as $realmsAffection) {
    $realmsAffectionsInCzech[] = $realmsAffection->getAffectionPeriod()->translateTo('cs') . ' ' . $realmsAffection->getValue();
}
$realmAffectionName = \count($realmsAffections) > 1
    ? 'náklonnosti'
    : 'náklonnost';
$realmsAffectionsResult = \implode(', ', $realmsAffectionsInCzech);
$resultParts[] = <<<HTML
{$realmAffectionName}: <strong>{$realmsAffectionsResult}</strong>
HTML;
$evocation = $currentFormula->getCurrentEvocation();
$evocationTime = $evocation->getEvocationTime(Tables::getIt()->getTimeTable());
$evocationUnitInCzech = $evocationTime->getUnitCode()->translateTo('cs', $evocationTime->getValue());
$evocationTimeResult = ($evocation->getValue() >= 0 ? '+' : '') . $evocation->getValue();
$evocationTimeResult .= " ({$evocationTime->getValue()} {$evocationUnitInCzech}";
if (($evocationTimeInMinutes = $evocationTime->findMinutes()) && $evocationTime->getUnitCode()->getValue() === TimeUnitCode::ROUND) {
    $evocationInMinutesUnitInCzech = $evocationTimeInMinutes->getUnitCode()->translateTo('cs', $evocationTimeInMinutes->getValue());
    $evocationTimeResult .= '; ' . $evocationTimeInMinutes->getValue() . ' ' . $evocationInMinutesUnitInCzech;
}
$evocationTimeResult .= ')';
$resultParts[] = <<<HTML
vyvolání (příprava formule): <strong>{$evocationTimeResult}</strong>
HTML;
$castingRounds = $currentFormula->getCurrentCastingRounds();
$castingBonus = $castingRounds->getTime(Tables::getIt()->getTimeTable())->getBonus();
$casting = $castingBonus->getTime();
$castingUnitInCzech = $casting->getUnitCode()->translateTo('cs', $casting->getValue());
$castingText = ($castingBonus->getValue() >= 0 ? '+' : '') . "{$castingBonus->getValue()} ({$casting->getValue()} {$castingUnitInCzech})";
$resultParts[] = <<<HTML
seslání (vypuštění kouzla): <strong>{$castingText}</strong>
HTML;
/** @noinspection PhpUnhandledExceptionInspection */
$duration = $currentFormula->getCurrentDuration();
$durationTime = $duration->getDurationTime(Tables::getIt()->getTimeTable());
$durationUnitInCzech = $durationTime->getUnitCode()->translateTo('cs', $durationTime->getValue());
$durationResult = ($duration->getValue() >= 0 ? '+' : '') . "{$duration->getValue()} ({$durationTime->getValue()} {$durationUnitInCzech})";
$resultParts[] = <<<HTML
    doba trvání: <strong>{$durationResult}</strong>
HTML;
/** @noinspection PhpUnhandledExceptionInspection */
$radius = $currentFormula->getCurrentRadius();
if ($radius !== null) {
    $radiusNameInCzech = FormulaMutableSpellParameterCode::getIt(FormulaMutableSpellParameterCode::RADIUS)->translateTo('cs');
    $radiusDistance = $radius->getDistance(Tables::getIt()->getDistanceTable());
    $radiusUnitInCzech = $radiusDistance->getUnitCode()->translateTo('cs', $radiusDistance->getValue());
    $radiusResult = ($radius->getValue() >= 0 ? '+' : '') . "{$radius->getValue()} ({$radiusDistance->getValue()}
            {$radiusUnitInCzech})";
    $resultParts[] = <<<HTML
          {$radiusNameInCzech}: <strong>{$radiusResult}</strong>
HTML;
}
/** @noinspection PhpUnhandledExceptionInspection */
$power = $currentFormula->getCurrentPower();
if ($power !== null) {
    $powerResult = ($power->getValue() >= 0 ? '+' : '') . $power->getValue();
    $resultParts[] = <<<HTML
síla: <strong>{$powerResult}</strong>
HTML;
}
/** @noinspection PhpUnhandledExceptionInspection */
$epicenterShiftOfModified = $currentFormula->getCurrentEpicenterShift();
if ($epicenterShiftOfModified !== null) {
    $epicenterShiftDistance = $epicenterShiftOfModified->getDistance(Tables::getIt()->getDistanceTable());
    $epicenterShiftUnitInCzech = $epicenterShiftDistance->getUnitCode()->translateTo('cs', $epicenterShiftDistance->getValue());
    $epicenterShiftResult = ($epicenterShiftOfModified->getValue() >= 0 ? '+' : '') .
        "{$epicenterShiftOfModified->getValue()} ({$epicenterShiftDistance->getValue()} {$epicenterShiftUnitInCzech})";
    $resultParts[] = <<<HTML
posun transpozicí: <strong>{$epicenterShiftResult}</strong>
HTML;
}
/** @noinspection PhpUnhandledExceptionInspection */
$detailLevel = $currentFormula->getCurrentDetailLevel();
if ($detailLevel !== null) {
    $resultParts[] = <<<HTML
detailnost: <strong>{$currentFormulaValues->formatNumber($detailLevel)}</strong>
HTML;
}
/** @noinspection PhpUnhandledExceptionInspection */
$sizeChange = $currentFormula->getCurrentSizeChange();
if ($sizeChange !== null) {
    $resultParts[] = <<<HTML
změna velikosti: <strong>{$currentFormulaValues->formatNumber($sizeChange)}</strong>
HTML;
}
/** @noinspection PhpUnhandledExceptionInspection */
$brightness = $currentFormula->getCurrentBrightness();
if ($brightness !== null) {
    $resultParts[] = <<<HTML
jas: {$currentFormulaValues->formatNumber($brightness)}
HTML;
}
/** @noinspection PhpUnhandledExceptionInspection */
$spellSpeed = $currentFormula->getCurrentSpellSpeed();
if ($spellSpeed !== null) {
    $speed = $spellSpeed->getSpeed(Tables::getIt()->getSpeedTable());
    $spellSpeedUnitInCzech = $speed->getUnitCode()->translateTo('cs', $speed->getValue());
    $resultParts[] = <<<HTML
rychlost: {$currentFormulaValues->formatNumber($spellSpeed)} ({$speed->getValue()} {$spellSpeedUnitInCzech})
HTML;
}
/** @noinspection PhpUnhandledExceptionInspection */
$attack = $currentFormula->getCurrentAttack();
if ($attack !== null) {
    $resultParts[] = <<<HTML
útočnost: {$currentFormulaValues->formatNumber($attack)}
HTML;
} ?>
<div id="result" class="row">
    <?php
    $columnCount = 0;
    foreach ($resultParts as $resultPart) {
        if ($columnCount > 0 && $columnCount % 3 === 0) { ?>
          <div class="row">
        <?php } ?>
      <div class="col-sm-4"><?= $resultPart ?></div>
        <?php if (($columnCount + 1) % 3 === 0) { ?>
        </div>
        <?php }
        $columnCount++;
    }
    unset($columnCount);
    ?>
</div>