<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Spells\FormulasTable;
use DrdPlus\Theurgist\Spells\ModifiersTable;
use DrdPlus\Theurgist\Spells\SpellTraitsTable;

require_once __DIR__ . '/vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', '1');

$modifiersTable = new ModifiersTable(Tables::getIt());
$spellTraitsTable = new SpellTraitsTable();
$formulasTable = new FormulasTable(Tables::getIt(), $modifiersTable, $spellTraitsTable);
$controller = new Controller($formulasTable, $modifiersTable, $spellTraitsTable, Tables::getIt()->getDistanceTable());
$selectedFormula = $controller->getSelectedFormula();
$selectedFormulaCode = $selectedFormula->getFormulaCode();
?>
<!DOCTYPE html>
<html lang="cs" xmlns="http://www.w3.org/1999/html">
<head>
    <title>Formule pro DrD+ theurga</title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/graphics.css">
    <link rel="stylesheet" type="text/css" href="css/socials.css">
    <noscript>
        <link rel="stylesheet" type="text/css" href="css/no_script.css">
    </noscript>
    <script type="text/javascript" src="js/facebook.js" async></script>
</head>
<body>
<div id="fb-root"></div>
<div class="background"></div>
<div>
    <form class="block delete" action="" method="post" onsubmit="return window.confirm('Opravdu smazat?')">
        <label>
            <input type="submit" value="Smazat" name="<?= $controller::DELETE_THEURGIST_CONFIGURATOR_HISTORY ?>">
            <span class="hint">(včetně paměti uložené v cookies)</span>
        </label>
    </form>
    <form id="configurator" class="block" action="" method="get">
        <div class="block remember">
            <label><input type="checkbox" name="<?= $controller::REMEMBER ?>" value="1"
                          <?php if ($controller->shouldRemember()) { ?>checked="checked"<?php } ?>>
                Pamatovat <span class="hint">(i při zavření prohlížeče)</span></label>
        </div>
        <div class="block">
            <input type="hidden" name="<?= $controller::PREVIOUS_FORMULA ?>"
                   value="<?= $selectedFormulaCode->getValue() ?>">
            <?php require __DIR__ . '/formula/formula.php'; ?>
            <hr class="clear">
            <?php require __DIR__ . '/modifiers/modifiers.php' ?>
            <hr class="clear">
            <button type="submit">Vybrat</button>
        </div>
    </form>
</div>
<div id="result" class="result">
    <?php require __DIR__ . '/result.php'; ?>
</div>
<div class="block issues">
    <a href="https://github.com/jaroslavtyc/drd-plus-theurgist-configurator/issues">Máš nápad 😀? Vidíš chybu 😱?️ Sem s
        tím!</a>
</div>
<div class="block">
    <div class="fb-like facebook"
         data-href="https://formule.theurg.drdplus.info/<?= $_SERVER['QUERY_STRING'] ? ('?' . $_SERVER['QUERY_STRING']) : '' ?>"
         data-layout="button" data-action="recommend"
         data-size="small" data-show-faces="false" data-share="true"></div>
    <a href="https://github.com/jaroslavtyc/drd-plus-theurgist-configurator/"
       title="Fork me on GitHub"><img class="github" src="/images/GitHub-Mark-64px.png"></a>
</div>
<script src="js/main.js"></script>
</body>
</html>