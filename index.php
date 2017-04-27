<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Codes\FormulaCode;
use DrdPlus\Theurgist\Formulas\CastingParameters\Affection;
use DrdPlus\Theurgist\Formulas\FormulasTable;
use DrdPlus\Theurgist\Formulas\ModifiersTable;
use DrdPlus\Theurgist\Formulas\SpellTraitsTable;

require_once __DIR__ . '/vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', '1');

$modifiersTable = new ModifiersTable(Tables::getIt());
$controller = new IndexController($modifiersTable);
$selectedFormula = $controller->getSelectedFormula();

$spellTraitsTable = new SpellTraitsTable();
$formulasTable = new FormulasTable(Tables::getIt(), $modifiersTable, $spellTraitsTable);
?>
<!DOCTYPE html>
<html lang="cs" xmlns="http://www.w3.org/1999/html">
<head>
    <title>Formule pro DrD+ theurga</title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/socials.css">
    <noscript>
        <link rel="stylesheet" type="text/css" href="css/no_script.css">
    </noscript>
    <script src="js/main.js"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/github-fork-ribbon-css/0.2.0/gh-fork-ribbon.min.css"/>
    <script type="text/javascript" src="js/facebook.js"></script>
</head>
<body>
<div id="fb-root"></div>
<div>
    <form id="configurator" class="body" method="get">
        <input type="hidden" name="previousFormula" value="<?= $selectedFormula ?>">
        <div class="block">
            <div class="panel">
                <label>Formule:
                    <select id="formula" name="formula">
                        <?php
                        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
                            ?>
                            <option value="<?= $formulaValue ?>"
                                    <?php if ($formulaValue === $selectedFormula->getValue()): ?>selected<?php endif ?>>
                                <?= FormulaCode::getIt($formulaValue)->translateTo('cs') ?>
                            </option>
                        <?php } ?>
                    </select>
                </label>
                <button type="submit">Vybrat</button>
            </div>
            <span class="panel forms">
                    (Forma: <?php
                $forms = [];
                foreach ($formulasTable->getForms($selectedFormula) as $formCode) {
                    $forms[] = $formCode->translateTo('cs');
                }
                echo implode(', ', $forms);
                ?>)
            </span>
        </div>
        <div id="modifiers" class="block">
            <div>Modifikátory:</div>
            <?php require __DIR__ . '/possibleModifiersOfFormula.php' ?>
        </div>
        <button type="submit">Vybrat</button>
    </form>
</div>
<div class="footer">
    <?php
    $selectedModifiers = $controller->getSelectedModifiers();
    $selectedSpellTraits = [];
    ?>
    <div>
        Sféra:
        <?php
        $realmOfModified = $formulasTable->getRealmOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits); ?>
        <ol class="realm" start="<?= $realmOfModified->getValue() ?>">
            <li></li>
        </ol>
    </div>
    <div>
        Náročnost: <?= $formulasTable->getDifficultyOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits) ?>
    </div>
    <div>
        <?php
        $affectionsOfModified = $formulasTable->getAffectionsOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
        if (count($affectionsOfModified) > 1):?>
            Náklonnosti:
        <?php else: ?>
            Náklonnost:
        <?php endif;
        $inCzech = [];
        /** @var Affection $affectionOfModified */
        foreach ($affectionsOfModified as $affectionOfModified) {
            $inCzech[] = $affectionOfModified->getAffectionPeriod()->translateTo('cs') . ' ' . $affectionOfModified->getValue();
        }
        echo implode(', ', $inCzech);
        ?>
    </div>
    <div>
        Vyvolání (příprava formule):
        <?php $evocationOfModified = $formulasTable->getEvocationOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
        $evocationTime = $evocationOfModified->getEvocationTime(Tables::getIt()->getTimeTable());
        $evocationUnitInCzech = $evocationTime->getUnitCode()->translateTo('cs', $evocationTime->getValue());
        echo ($evocationOfModified->getValue() >= 0 ? '+' : '')
            . "{$evocationOfModified->getValue()}  ({$evocationTime->getValue()} {$evocationUnitInCzech})";
        ?>
    </div>
    <div>
        Seslání (vypuštění kouzla):
        <?php $castingOfModified = $formulasTable->getCastingOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
        $castingBonus = $castingOfModified->getBonus();
        $castingUnitInCzech = $castingOfModified->getUnitCode()->translateTo('cs', $castingOfModified->getValue());
        echo ($castingBonus->getValue() >= 0 ? '+' : '')
            . "{$castingBonus->getValue()}  ({$castingOfModified->getValue()} {$castingUnitInCzech})";
        ?>
    </div>
    <div>
        Doba trvání:
        <?php $durationOfModified = $formulasTable->getDurationOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
        $durationTime = $durationOfModified->getDurationTime(Tables::getIt()->getTimeTable());
        $durationUnitInCzech = $durationTime->getUnitCode()->translateTo('cs', $durationTime->getValue());
        echo ($durationOfModified->getValue() >= 0 ? '+' : '')
            . "{$durationOfModified->getValue()}  ({$durationTime->getValue()} {$durationUnitInCzech})";
        ?>
    </div>
    <?php $radiusOfModified = $formulasTable->getRadiusOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
    if ($radiusOfModified !== null) { ?>
        <div>
            Poloměr:
            <?php $radiusDistance = $radiusOfModified->getDistance(Tables::getIt()->getDistanceTable());
            $radiusUnitInCzech = $radiusDistance->getUnitCode()->translateTo('cs', $radiusDistance->getValue());
            echo ($radiusOfModified->getValue() >= 0 ? '+' : '')
                . "{$radiusOfModified->getValue()} ({$radiusDistance->getValue()} {$radiusUnitInCzech})";
            ?>
        </div>
    <?php }
    $powerOfModified = $formulasTable->getPowerOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
    if ($powerOfModified !== null) { ?>
        <div>
            Síla:
            <?= ($powerOfModified->getValue() >= 0 ? '+' : '') . $powerOfModified->getValue(); ?>
        </div>
    <?php }
    $epicenterShiftOfModified = $formulasTable->getEpicenterShiftOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
    if ($epicenterShiftOfModified !== null) {
        $epicenterShiftDistance = $epicenterShiftOfModified->getDistance(Tables::getIt()->getDistanceTable());
        $epicenterShiftUnitInCzech = $epicenterShiftDistance->getUnitCode()->translateTo('cs', $epicenterShiftDistance->getValue());
        ?>
        <div>
            Posun transpozicí:
            <?= ($epicenterShiftOfModified->getValue() >= 0 ? '+' : '') .
            "{$epicenterShiftOfModified->getValue()} ({$epicenterShiftDistance->getValue()} {$epicenterShiftUnitInCzech})" ?>
        </div>
    <?php }
    $detailLevelOfModified = $formulasTable->getDetailLevelOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
    if ($detailLevelOfModified !== null) {
        ?>
        <div>
            Detailnost:
            <?= ($detailLevelOfModified->getValue() >= 0 ? '+' : '') . $detailLevelOfModified->getValue() ?>
        </div>
    <?php }
    $sizeChangeOfModified = $formulasTable->getSizeChangeOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
    if ($sizeChangeOfModified !== null) {
        ?>
        <div>
            Změna velikosti:
            <?= ($sizeChangeOfModified->getValue() >= 0 ? '+' : '') . $sizeChangeOfModified->getValue() ?>
        </div>
    <?php }
    $brightnessOfModified = $formulasTable->getBrightnessOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
    if ($brightnessOfModified !== null) {
        ?>
        <div>
            Jas:
            <?= ($brightnessOfModified->getValue() >= 0 ? '+' : '') . $brightnessOfModified->getValue() ?>
        </div>
    <?php }
    $spellSpeedOfModified = $formulasTable->getSpellSpeedOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
    if ($spellSpeedOfModified !== null) {
        $spellSpeed = $spellSpeedOfModified->getSpeed(Tables::getIt()->getSpeedTable());
        $spellSpeedUnitInCzech = $spellSpeed->getUnitCode()->translateTo('cs', $spellSpeed->getValue());
        ?>
        <div>
            Rychlost:
            <?= ($spellSpeedOfModified->getValue() >= 0 ? '+' : '') .
            "{$spellSpeedOfModified->getValue()} ({$spellSpeed->getValue()} {$spellSpeedUnitInCzech})" ?>
        </div>
    <?php }
    $attackOfModified = $formulasTable->getAttackOfModified($selectedFormula, $selectedModifiers, $selectedSpellTraits);
    if ($attackOfModified !== null) { ?>
        <div>
            Útočnost: <?= ($attackOfModified->getValue() >= 0 ? '+' : '') . $attackOfModified->getValue(); ?>
        </div>
    <?php }
    ?>
</div>
<div class="block">
    <div class="fb-like facebook"
         data-href="https://formule.theurg.drdplus.info/<?= $_SERVER['QUERY_STRING'] ? ('?' . $_SERVER['QUERY_STRING']) : '' ?>"
         data-layout="button" data-action="recommend"
         data-size="small" data-show-faces="false" data-share="true"></div>
    <a class="github-fork-ribbon right-bottom fixed"
       href="https://github.com/jaroslavtyc/drd-plus-theurgist-configurator/"
       title="Fork me on GitHub">Fork me</a>
</div>
</body>
</html>